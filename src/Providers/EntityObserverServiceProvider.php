<?php

namespace Corals\Modules\Entity\Providers;

use Corals\Modules\Entity\Models\Entity;
use Corals\Modules\Entity\Observers\EntityObserver;
use Illuminate\Support\ServiceProvider;

class EntityObserverServiceProvider extends ServiceProvider
{
    /**
     * Register Observers
     */
    public function boot()
    {

        Entity::observe(EntityObserver::class);
    }
}
