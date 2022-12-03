<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Acelle\Model\User;
use Acelle\Model\CustomerGroup;

class CustomerGroupPolicy
{
    use HandlesAuthorization;

    public function read(User $user, CustomerGroup $item)
    {
        $can = $user->admin->getPermission('customer_group_read') != 'no';

        return $can;
    }

    public function read_all(User $user, CustomerGroup $item)
    {
        $can = $user->admin->getPermission('customer_group_read') == 'all';

        return $can;
    }

    public function create(User $user, CustomerGroup $item)
    {
        $can = $user->admin->getPermission('customer_group_create') == 'yes';

        return $can;
    }

    public function update(User $user, CustomerGroup $item)
    {
        $ability = $user->admin->getPermission('customer_group_update');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->admin->id == $item->admin_id);

        return $can;
    }

    public function sort(User $user, CustomerGroup $item)
    {
        $ability = $user->admin->getPermission('customer_group_update');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->admin->id == $item->admin_id);

        return $can;
    }

    public function delete(User $user, CustomerGroup $item)
    {
        $ability = $user->admin->getPermission('customer_group_delete');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->admin->id == $item->admin_id);

        return $can;
    }
}
