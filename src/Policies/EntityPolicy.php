<?php

namespace Corals\Modules\Entity\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Modules\Entity\Models\Entity;
use Corals\User\Models\User;

class EntityPolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.entity';

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('Entity::entity.view')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->can('Entity::entity.create');
    }

    /**
     * @param User $user
     * @param Entity $entity
     * @return bool
     */
    public function update(User $user, Entity $entity)
    {
        if ($user->can('Entity::entity.update')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param Entity $entity
     * @return bool
     */
    public function destroy(User $user, Entity $entity)
    {
        if ($user->can('Entity::entity.delete')) {
            return true;
        }
        return false;
    }

}
