<?php

namespace Corals\Modules\Entity\Facades;

use Illuminate\Support\Facades\Facade;

class EntityFacade extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return \Corals\Modules\Entity\Classes\Entity::class;
    }
}
