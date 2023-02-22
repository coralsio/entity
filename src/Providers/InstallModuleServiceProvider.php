<?php

namespace Corals\Modules\Entity\Providers;

use Corals\Foundation\Providers\BaseInstallModuleServiceProvider;
use Corals\Modules\Entity\database\migrations\EntityTables;
use Corals\Modules\Entity\database\seeds\EntityDatabaseSeeder;

class InstallModuleServiceProvider extends BaseInstallModuleServiceProvider
{
    protected $module_public_path = __DIR__ . '/../public';

    protected $migrations = [
        EntityTables::class,
    ];

    protected function providerBooted()
    {
        $this->createSchema();

        $entityDatabaseSeeder = new EntityDatabaseSeeder();

        $entityDatabaseSeeder->run();
    }
}
