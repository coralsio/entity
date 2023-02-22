<?php

namespace Corals\Modules\Entity\Classes;

use Corals\Modules\Entity\Models\Entity as EntityModel;
use Corals\Modules\Entity\Models\Entry;
use Corals\Modules\Utility\Category\Facades\Category;
use Corals\Settings\Facades\CustomFields;
use Illuminate\Database\Eloquent\Relations\Relation;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Entity
{
    /**
     * @param $entity
     * @return array
     */
    public function getDisplayableEntityFields($entity): array
    {
        $entity = $this->normalizeEntityModel($entity);

        return CustomFields::getSortedFields($entity)
            ->filter(function ($field) {
                return CustomFields::getAttribute($field, 'field_config.show_in_list', false);
            })->toArray();
    }

    /**
     * @param $entity
     * @param $type
     * @return array
     */
    public function getFullTextSearchEntityFields($entity, $type): array
    {
        $entity = $this->normalizeEntityModel($entity);

        return CustomFields::getSortedFields($entity)
            ->filter(function ($field) use ($type) {
                $fullTextAttribute = CustomFields::getAttribute($field, 'field_config.full_text_search', []) ?? [];

                return array_search($type, $fullTextAttribute) !== false;
            })->toArray();
    }

    /**
     * @param $entity
     * @return array
     */
    public function getDisplayableColumnsForDatatable($entity): array
    {
        $entity = $this->normalizeEntityModel($entity);

        foreach ($this->getDisplayableEntityFields($entity) as $column) {
            $isOrderable = CustomFields::getAttribute($column, 'field_config.sortable', false) ? 'true' : 'false';

            $displayableColumns[CustomFields::getAttribute($column, 'name')] = [
                'title' => CustomFields::getAttribute($column, 'label', CustomFields::getAttribute($column, 'name')),
                'orderable' => $isOrderable,
                'searchable' => CustomFields::getAttribute($column, 'field_config.searchable', false),
                'class' => CustomFields::getAttribute($column, 'field_config.grid_class', ''),
            ];
        }

        return $displayableColumns ?? [];
    }

    /**
     * @param $entity
     * @param Entry $entry
     * @return array
     */
    public function getDisplayableColumnsForTransformer($entity, Entry $entry): array
    {
        $entity = $this->normalizeEntityModel($entity);

        foreach ($entity->fields as $column) {
            $name = CustomFields::getAttribute($column, 'name');
            $value = $entry->getProperty($name, null, null, 'values');
            $type = CustomFields::getAttribute($column, 'type');
            $fieldValues[$name] = $this->castValue($type, $value, $this->getFieldSetting($entity, $name));

            if (CustomFields::getAttribute($column, 'field_config.is_identifier', false) && ! is_api_request()) {
                $fieldValues[$name] = sprintf("<a href='%s'>%s</a>", $entry->getShowURL(), $fieldValues[$name]);
            }
        }

        return $fieldValues ?? [];
    }

    /**
     * @param $entity
     * @return array
     */
    public function getFilterableColumns($entity)
    {
        $entity = $this->normalizeEntityModel($entity);

        $filters = [];

        foreach ($this->getDisplayableEntityFields($entity) as $column) {
            $name = CustomFields::getAttribute($column, 'name');

            $type = CustomFields::getAttribute($column, 'type');

            $options = [];
            if ($type == 'select') {
                $options = CustomFields::getAttribute($column, 'options', []);
                $options = array_combine(data_get($options, '*.key'), data_get($options, '*.value'));
            }


            $filters[$name] = [
                'title' => CustomFields::getAttribute($column, 'label', CustomFields::getAttribute($column, 'name')),
                'class' => 'col-md-2',
                'type' => $type,
                'options' => $options,
                'is_json' => true,
                'condition' => 'like',
                'active' => true,
                'json_column' => 'values',
            ];
        }

        return $filters ?? [];
    }

    protected function castValue($type, $value, $fieldSetting)
    {
        if (is_api_request()) {
            return $value;
        }

        if (! $value) {
            return '-';
        }

        $value = is_array($value) ? join(',', $value) : $value;

        switch ($type) {
            case 'boolean':
            case 'bool':
            case 'checkbox':
                $value = yesNoFormatter($value);

                break;
            case 'date':
                $value = format_date($value);

                break;
            case 'file':
                $media = Media::find($value);
                $value = sprintf(
                    "<a href='%s' target='_blank'><i class='fa fa-external-link'></i> %s</a>",
                    $media->getFullUrl(),
                    $media->name
                );

                break;
            case 'multi_values':
            case 'select':

                if (data_get($fieldSetting, 'options')) {
                    $options = collect($fieldSetting->options);
                    $result = [];
                    $values = explode(',', $value);

                    foreach ($values as $v) {
                        $result[] = $options->where('key', $v)->first()['value'];
                    }
                    $value = join(',', $result);

                    break;
                } elseif (data_get($fieldSetting, 'options_setting')) {
                    $optionsSetting = collect($fieldSetting->options_setting);

                    if ($optionsSetting['source'] == 'database') {
                        $model = Relation::$morphMap[$optionsSetting['source_model']] ?? $optionsSetting['source_model'];

                        $column = $optionsSetting['source_model_column'];

                        $value = with(new $model())->where('id', $value)->first()->{$column};

                        break;
                    }
                }

                // no break
            case 'color':
                $value = "<div style=\"display:inline-block;background-color:{$value};height: 100%;width: 25px;\">&nbsp;</div>";


                break;
        }

        return $value;
    }

    /**
     * @param Entry $entry
     * @param $entity
     * @return string
     */
    public function renderEntryFieldsValues(Entry $entry, $entity)
    {
        $entity = $this->normalizeEntityModel($entity);

        if (! $entry->values) {
            return '';
        }

        $rows = '';

        foreach (CustomFields::getSortedFields($entity) as $entityAttributes) {
            $field = $entityAttributes['name'];
            $value = CustomFields::getAttribute($entry->values, $field);

            $fieldSetting = $this->getFieldSetting($entity, $field);

            $value = $this->castValue($fieldSetting->type, $value, $fieldSetting);

            $rows .= sprintf("<tr><td>%s</td><td>%s</td></tr>", $fieldSetting->label, $value);
        }

        if ($category = $entity->categories()->first()) {
            foreach ($entry->categories as $category) {
                $categoryAttributesValues = [];

                foreach ($category->categoryAttributes as $attribute) {
                    $categoryAttributesValues[] = formatArrayAsLabels(Category::renderAttribute($attribute, $entry, [], false), 'info', null, true);
                }

                $categoriesLabels [] = sprintf("%s %s", formatArrayAsLabels([$category->name], 'success', '<i class="fa fa-folder-open"></i>'), join("", $categoryAttributesValues));
            }

            $rows .= sprintf(
                "<tr><td>%s</td><td>%s </td></tr>",
                __('Entity::attributes.entry.categories'),
                join("<br>", $categoriesLabels ?? [])
            );
        }

        if ($entity->has_tags) {
            $rows .= sprintf(
                "<tr><td>%s</td><td>%s</td></tr>",
                __('Entity::attributes.entry.tags'),
                $entry->present('tags')
            );
        }

        return sprintf("<div class='table-responsive entry-table'>
                        <table class='table table-striped'>%s</table></div>", $rows);
    }

    /**
     * @param $entity
     * @param $field
     * @return object
     */
    protected function getFieldSetting($entity, $field): object
    {
        $entity = $this->normalizeEntityModel($entity);

        return (object)collect($entity->fields)->where('name', $field)->first();
    }

    /**
     * @param $entity
     * @return array
     */
    public function getEntityIdentifiers($entity)
    {
        $entity = $this->normalizeEntityModel($entity);

        return collect($entity->fields)
            ->filter(function ($field) {
                return data_get($field, 'field_config.is_identifier');
            })->pluck('name')
            ->toArray();
    }

    /**
     * @param $model
     * @return mixed
     */
    public function normalizeEntityModel($model)
    {
        if (is_object($model)) {
            return $model;
        }

        if (is_string($model)) {
            $model = EntityModel::find($model);
        }

        if (! $model) {
            $model = EntityModel::findByHash($model);
        }

        return $model;
    }
}
