<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Acelle\Model\User;
use Acelle\Model\Admin;

class AdminPolicy
{
    use HandlesAuthorization;

    public function read(User $user, Admin $item)
    {
        $can = $user->admin->getPermission('admin_read') != 'no';

        return $can;
    }

    public function readAll(User $user, Admin $item)
    {
        $can = $user->admin->getPermission('admin_read') == 'all';

        return $can;
    }

    public function create(User $user, Admin $item)
    {
        $can = $user->admin->getPermission('admin_create') == 'yes';

        return $can;
    }

    public function profile(User $user, Admin $item)
    {
        return $user->id == $item->user_id;
    }

    public function update(User $user, Admin $item)
    {
        $ability = $user->admin->getPermission('admin_update');
        $can = $ability == 'all'
            || ($ability == 'own' && $user->id == $item->creator_id);

        return $can;
    }

    public function delete(User $user, Admin $item)
    {
        $ability = $user->admin->getPermission('admin_delete');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->id == $item->creator_id);

        return $can && $item->customers()->count() == 0 && $item->id !== $user->admin->id;
    }

    public function forceDelete(User $user, Admin $item)
    {
        $ability = $user->admin->getPermission('admin_delete');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->id == $item->creator_id);

        return $can && $item->id !== $user->admin->id;
    }

    public function loginAs(User $user, Admin $item)
    {
        $ability = $user->admin->getPermission('admin_login_as');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->id == $item->creator_id);

        return $can && $user->admin->id != $item->id;
    }

    public function disable(User $user, Admin $item)
    {
        $ability = $user->admin->getPermission('admin_update');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->id == $item->creator_id);

        return $can && $item->status != 'inactive';
    }

    public function enable(User $user, Admin $item)
    {
        $ability = $user->admin->getPermission('admin_update');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->id == $item->creator_id);

        return $can && $item->status != 'active';
    }
}
