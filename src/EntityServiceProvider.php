<?php

namespace Corals\Modules\Entity;

use Corals\Foundation\Providers\BasePackageServiceProvider;
use Corals\Modules\Entity\Facades\EntityFacade;
use Corals\Modules\Entity\Models\Entity;
use Corals\Modules\Entity\Models\Entry;
use Corals\Modules\Entity\Providers\EntityAuthServiceProvider;
use Corals\Modules\Entity\Providers\EntityObserverServiceProvider;
use Corals\Modules\Entity\Providers\EntityRouteServiceProvider;
use Corals\Settings\Facades\Modules;
use Corals\Settings\Facades\Settings;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\AliasLoader;

class EntityServiceProvider extends BasePackageServiceProvider
{
    /**
     * @var
     */
    protected $defer = true;
    /**
     * @var
     */
    protected $packageCode = 'corals-entity';

    /**
     * Bootstrap the application events.
     *
     * @return void
     */

    public function bootPackage()
    {
        // Load view
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'Entity');

        // Load translation
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'Entity');

        // Load migrations
//        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->registerMorphMaps();
        $this->registerCustomFieldsModels();

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function registerPackage()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/entity.php', 'entity');

        $this->app->register(EntityRouteServiceProvider::class);
        $this->app->register(EntityAuthServiceProvider::class);
        $this->app->register(EntityObserverServiceProvider::class);

        $this->app->booted(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('EntityFacade', EntityFacade::class);
        });
    }

    protected function registerCustomFieldsModels()
    {
        Settings::addCustomFieldModel(Entity::class);
        Settings::addCustomFieldModel(Entry::class);
    }

    protected function registerMorphMaps(): void
    {
        Relation::morphMap([
            'Entity' => Entity::class,
            'Entry' => Entry::class
        ]);
    }

    public function registerModulesPackages()
    {
        Modules::addModulesPackages('corals/entity');
    }
}
