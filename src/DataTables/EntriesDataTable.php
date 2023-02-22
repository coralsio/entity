<?php

namespace Corals\Modules\Entity\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\Entity\Facades\EntityFacade;
use Corals\Modules\Entity\Models\Entry;
use Corals\Modules\Entity\Transformers\EntryTransformer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Yajra\DataTables\EloquentDataTable;

class EntriesDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('entity.models.entry.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new EntryTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param Entry $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(Entry $model)
    {
        $entity = EntityFacade::normalizeEntityModel($this->request->route('entity'));

        $query = $model->newQuery()
            ->with('entity')
            ->where('entity_id', $entity->id)
            ->join('entity_entities', 'entity_entries.entity_id', 'entity_entities.id')
            ->addSelect("entity_entries.*", 'entity_entities.code as entity_code');

        $this->selectSortableJsonColumns($query);

        return $query;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $entity = $this->request->route('entity');

        return array_merge([
            'id' => ['visible' => false],
        ], EntityFacade::getDisplayableColumnsForDatatable($entity), [
            'entity_code' => ['title' => trans('Entity::attributes.entry.entity_code')],
            'updated_at' => ['title' => trans('Corals::attributes.updated_at')],
        ]);
    }

    public function getFilters()
    {
        return EntityFacade::getFilterableColumns($this->request->route('entity'));
    }

    /**
     * select json column values, to make sorting possible
     *
     * @param Builder $query
     * @param string $jsonColumnName
     */
    protected function selectSortableJsonColumns(Builder $query, $jsonColumnName = 'values'): void
    {
        $orderByColumn = Arr::first($this->request()->get('order', []));

        $orderByColumnIndex = data_get($orderByColumn, 'column');

        $columns = $this->request()->get('columns');

        $columnName = $columns[$orderByColumnIndex]['data'];

        $jsonSortableColumns = EntityFacade::getDisplayableColumnsForDatatable($this->request->route('entity'));

        if (! in_array($columnName, array_keys($jsonSortableColumns))) {
            return;
        }

        $query->addSelect(\DB::raw(sprintf("json_extract(`%s`,'$.%s') as %s", $jsonColumnName, $columnName, $columnName)));
    }
}
