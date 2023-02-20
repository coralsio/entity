<?php

namespace Corals\Modules\Entity\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\Entity\Models\Entity;
use Corals\Modules\Entity\Transformers\EntityTransformer;
use Yajra\DataTables\EloquentDataTable;

class EntitiesDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('entity.models.entity.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new EntityTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param Entity $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(Entity $model)
    {
        return $model->newQuery();
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'id' => ['visible' => false],
            'code' => ['title' => trans('Entity::attributes.entity.code')],
            'name_plural' => ['title' => trans('Entity::attributes.entity.name_plural')],
            'name_singular' => ['title' => trans('Entity::attributes.entity.name_singular')],
            'updated_at' => ['title' => trans('Corals::attributes.updated_at')],
        ];
    }

    public function getFilters()
    {
        return [
            'code' => [
                'title' => trans('Entity::attributes.entity.code'),
                'class' => 'col-md-2',
                'type' => 'text',
                'condition' => 'like',
                'active' => true
            ],

            'name_plural' => [
                'title' => trans('Entity::attributes.entity.name_plural'),
                'class' => 'col-md-2',
                'type' => 'text',
                'condition' => 'like',
                'active' => true
            ],
            'name_singular' => [
                'title' => trans('Entity::attributes.entity.name_singular'),
                'class' => 'col-md-2',
                'type' => 'text',
                'condition' => 'like',
                'active' => true
            ],
        ];
    }
}
