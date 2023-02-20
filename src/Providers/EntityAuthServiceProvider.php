<?php

namespace Corals\Modules\Entity\Providers;

use Corals\Modules\Entity\Models\Entity;
use Corals\Modules\Entity\Models\Entry;
use Corals\Modules\Entity\Policies\EntityPolicy;
use Corals\Modules\Entity\Policies\EntryPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class EntityAuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Entity::class => EntityPolicy::class,
        Entry::class => EntryPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
