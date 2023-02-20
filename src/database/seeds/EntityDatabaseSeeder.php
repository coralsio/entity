<?php

namespace Corals\Modules\Entity\database\seeds;

use Corals\Menu\Models\Menu;
use Corals\Settings\Models\Setting;
use Corals\User\Models\Permission;
use Illuminate\Database\Seeder;
use \Spatie\MediaLibrary\MediaCollections\Models\Media;

class EntityDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(EntityPermissionsDatabaseSeeder::class);
        $this->call(EntityMenuDatabaseSeeder::class);
        $this->call(EntitySettingsDatabaseSeeder::class);
    }

    public function rollback()
    {
        Permission::where('name', 'like', 'Entity::%')->delete();

        Menu::where('key', 'entity')
            ->orWhere('active_menu_url', 'like', 'entity%')
            ->orWhere('url', 'like', 'entity%')
            ->delete();

        Setting::where('category', 'Entity')->delete();

        Media::whereIn('collection_name', ['entity-media-collection'])->delete();
    }
}
