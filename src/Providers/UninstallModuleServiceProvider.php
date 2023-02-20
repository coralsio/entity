<?php

namespace Corals\Modules\Entity\Providers;

use Corals\Foundation\Providers\BaseUninstallModuleServiceProvider;
use Corals\Modules\Entity\database\migrations\EntityTables;
use Corals\Modules\Entity\database\seeds\EntityDatabaseSeeder;

class UninstallModuleServiceProvider extends BaseUninstallModuleServiceProvider
{
    protected $migrations = [
        EntityTables::class
    ];

    protected function providerBooted()
    {
        $this->dropSchema();

        $entityDatabaseSeeder = new EntityDatabaseSeeder();

        $entityDatabaseSeeder->rollback();
    }
}
