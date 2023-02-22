<?php

namespace Corals\Modules\Entity\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Modules\Entity\Models\Entry;
use Corals\User\Models\User;

class EntryPolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.entity';

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->can('Entity::entry.view');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('Entity::entry.create');
    }

    /**
     * @param User $user
     * @param Entry $entry
     * @return bool
     */
    public function update(User $user, Entry $entry): bool
    {
        return $user->can('Entity::entry.update');
    }

    /**
     * @param User $user
     * @param Entry $entry
     * @return bool
     */
    public function destroy(User $user, Entry $entry): bool
    {
        return $user->can('Entity::entry.delete');
    }
}
