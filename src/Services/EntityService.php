<?php

namespace Corals\Modules\Entity\Services;

use Corals\Foundation\Services\BaseServiceClass;
use Corals\Menu\Models\Menu;

class EntityService extends BaseServiceClass
{
    protected $excludedRequestParams = ['categories'];

    public function postStoreUpdate($request, $additionalData)
    {
        $this->model->categories()->sync($request->get('categories'));

        $entityMenu = Menu::query()->where('key', 'entity')->first();

        if ($entityMenu) {
            $url = sprintf("%s/%s/entries", config('entity.models.entity.resource_url'), $this->model->hashed_id);

            Menu::query()->updateOrCreate(['key' => 'entity_' . $this->model->id], [
                'parent_id' => $entityMenu->id,
                'url' => $url,
                'active_menu_url' => $url . '*',
                'name' => $this->model->name_plural,
                'description' => $this->model->name_plural . ' List Menu Item',
                'icon' => 'fa fa-cube',
                'target' => null,
                'roles' => ['1'],
                'order' => 0,
            ]);
        }
    }
}
