<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Acelle\Model\User;
use Acelle\Model\Customer;

class CustomerPolicy
{
    use HandlesAuthorization;

    public function read(User $user, Customer $item)
    {
        $can = $user->admin->getPermission('customer_read') != 'no';

        return $can;
    }

    public function readAll(User $user, Customer $item)
    {
        $can = $user->admin->getPermission('customer_read') == 'all';

        return $can;
    }

    public function create(User $user, Customer $item)
    {
        $can = $user->admin->getPermission('customer_create') == 'yes';

        return $can;
    }

    public function profile(User $user, Customer $customer)
    {
        return $user->customer_id == $customer->id;
    }

    public function update(User $user, Customer $item)
    {
        $ability = $user->admin->getPermission('customer_update');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->admin->id == $item->admin_id);

        return $can;
    }

    public function delete(User $user, Customer $item)
    {
        $ability = $user->admin->getPermission('customer_delete');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->admin->id == $item->admin_id);

        return $can;
    }

    public function loginAs(User $user, Customer $item)
    {
        $ability = $user->admin->getPermission('customer_login_as');
        $can = $item->id != $user->customer_id && ($ability == 'all');
        return $can;
    }

    public function disable(User $user, Customer $item)
    {
        $ability = $user->admin->getPermission('customer_update');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->admin->id == $item->admin_id);

        return $can && $item->status != 'inactive';
    }

    public function enable(User $user, Customer $item)
    {
        $ability = $user->admin->getPermission('customer_update');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->admin->id == $item->admin_id);

        return $can && $item->status != 'active';
    }

    public function register(User $user, Customer $item)
    {
        $ability = \Acelle\Model\Setting::get('enable_user_registration') == 'yes';
        $can = $ability;

        return true;
    }

    public function viewSubAccount(User $user, Customer $item)
    {
        $ability = $user->admin->getPermission('customer_update');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->admin->id == $item->admin_id);
        $can = $can && $item->subAccounts()->count();
        return $can;
    }

    public function assignPlan(User $user, Customer $item)
    {
        $ability = $user->admin->getPermission('customer_update');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->admin->id == $item->admin_id);

        return $can;
    }
}
