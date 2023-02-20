<?php

namespace Corals\Modules\Entity\database\seeds;

use Illuminate\Database\Seeder;

class EntityMenuDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entity_menu_id = \DB::table('menus')->insertGetId([
            'parent_id' => 1,// admin
            'key' => 'entity',
            'url' => null,
            'active_menu_url' => 'entity/*',
            'name' => 'Entity',
            'description' => 'Entity Menu Item',
            'icon' => 'fa fa-globe',
            'target' => null,
            'roles' => '["1"]',
            'order' => 0,
        ]);

        // seed children menu
        \DB::table('menus')->insert(
            [
                [
                    'parent_id' => $entity_menu_id,
                    'key' => null,
                    'url' => config('entity.models.entity.resource_url'),
                    'active_menu_url' => config('entity.models.entity.resource_url') . '*',
                    'name' => 'Entities',
                    'description' => 'Entities List Menu Item',
                    'icon' => 'fa fa-cube',
                    'target' => null,
                    'roles' => '["1"]',
                    'order' => 0,
                ],
            ]
        );
    }
}
