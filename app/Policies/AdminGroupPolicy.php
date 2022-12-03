<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Acelle\Model\User;
use Acelle\Model\AdminGroup;

class AdminGroupPolicy
{
    use HandlesAuthorization;

    public function read(User $user, AdminGroup $item)
    {
        $can = $user->admin->getPermission('admin_group_read') != 'no';

        return $can;
    }

    public function readAll(User $user, AdminGroup $item)
    {
        $can = $user->admin->getPermission('admin_group_read') == 'all';

        return $can;
    }

    public function create(User $user, AdminGroup $item)
    {
        $can = $user->admin->getPermission('admin_group_create') == 'yes';

        return $can;
    }

    public function update(User $user, AdminGroup $item)
    {
        $ability = $user->admin->getPermission('admin_group_update');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->id == $item->creator_id);

        return $can;
    }

    public function sort(User $user, AdminGroup $item)
    {
        $ability = $user->admin->getPermission('admin_group_update');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->id == $item->creator_id);

        return $can;
    }

    public function delete(User $user, AdminGroup $item)
    {
        $ability = $user->admin->getPermission('admin_group_delete');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->id == $item->creator_id);

        return $can;
    }
}
