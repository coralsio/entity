<?php

namespace Corals\Modules\Entity\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\Entity\Models\Entity;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class EntityRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(Entity::class);

        return $this->isAuthorized();
    }

    /**
     * @return array
     * @throws ValidationException
     */
    public function rules()
    {
        $this->setModel(Entity::class);
        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
                'fields.*.type' => 'required|max:191',
                'fields.*.name' => 'required|max:191',
                'fields.*.label' => 'required|max:191',
                'fields.*.status' => 'required',
                'fields.*.options_setting.source' => 'required_if:type,select',
                'code' => 'required|max:191',
                'name_singular' => 'required|max:191',
                'name_plural' => 'required|max:191',
            ]);


            $hasIdentifier = false;

            foreach ($this->fields as $field) {
                if (data_get($field, 'field_config.is_identifier')) {
                    $hasIdentifier = true;

                    break;
                }
            }

            if (! $hasIdentifier) {
                $rules['identifier'] = 'required';
            }

            if (in_array($this->get('type'), ['select', 'radio', 'multi_values'])) {
                if ($this->get('options_setting')['source'] == "static") {
                    foreach ($this->get('options', []) as $id => $item) {
                        $rules = array_merge($rules, [
                            "options.{$id}.key" => 'required',
                            "options.{$id}.value" => 'required',
                        ]);
                    }
                } elseif ($this->get('options_setting')['source'] == "database") {
                    $rules = array_merge($rules, [
                        "options_setting.source_model" => 'required',
                        "options_setting.source_model_column" => 'required',
                    ]);
                }
            }

            foreach ($this->get('custom_attributes', []) as $id => $item) {
                $rules = array_merge($rules, [
                    "custom_attributes.{$id}.key" => 'required',
                    "custom_attributes.{$id}.value" => 'required',
                ]);
            }

            $this->validateFieldsNames();
        }

        if ($this->isStore()) {
            $rules = array_merge($rules, [
                'code' => 'required|max:191|unique:entity_entities,code',
            ]);
        }

        if ($this->isUpdate()) {
            $entity = $this->route('entity');

            $rules = array_merge($rules, [
                'code' => 'required|max:191|unique:entity_entities,code,' . $entity->id,
            ]);
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = [];

        foreach ($this->get('options', []) as $id => $item) {
            $attributes["fields.*.options.{$id}.key"] = 'Key';
            $attributes["fields.*.options.{$id}.value"] = 'Value';
        }

        foreach ($this->get('custom_attributes', []) as $id => $item) {
            $attributes["fields.*.custom_attributes.{$id}.key"] = 'Key';
            $attributes["fields.*.custom_attributes.{$id}.value"] = 'Value';
        }

        $attributes = array_merge($attributes, [
            'fields.*.type' => 'Type',
            'fields.*.name' => 'Name',
            'fields.*.label' => 'Label',
            'fields.*.status' => 'Status',
            'fields.*.options_setting.source' => 'Source',
            'fields.*.field_config.is_identifier' => 'identifier',
        ]);

        return $attributes;
    }

    public function messages()
    {
        return [
            'identifier.required' => trans('validation.at_least_one', ['attribute' => 'identifier']),
        ];
    }

    /**
     * @throws ValidationException
     */
    protected function validateFieldsNames()
    {
        $specialValidation = [];

        foreach ($this->fields as $index => $field) {
            $names = Arr::pluck(Arr::except($this->fields, $index), 'name', 'name');

            if (in_array(data_get($field, 'name'), $names)) {
                $specialValidation = array_merge($specialValidation, [
                    "fields.$index.name" => trans('validation.unique', ['attribute' => 'name']),
                ]);
            }
        }

        if (filled($specialValidation)) {
            throw ValidationException::withMessages($specialValidation);
        }
    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getValidatorInstance()
    {
        if ($this->isUpdate()) {
            $data = $this->all();

            $data['code'] = Str::slug(Arr::get($data, 'code'));
            $data['has_tags'] = Arr::get($data, 'has_tags', false);
            $data['has_gallery'] = Arr::get($data, 'has_gallery', false);
            $data['reviewable'] = Arr::get($data, 'reviewable', false);
            $data['wishlistable'] = Arr::get($data, 'wishlistable', false);

            foreach (data_get($data, 'fields', []) as $index => $field) {
                if (! empty($field['name'])) {
                    Arr::set($data, "fields.$index.name", Str::slug($field['name'], '_'));
                }
            }

            $this->getInputSource()->replace($data);
        }

        return parent::getValidatorInstance();
    }
}
