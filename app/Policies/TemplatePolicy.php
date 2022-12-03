<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Acelle\Model\User;
use Acelle\Model\Template;

class TemplatePolicy
{
    use HandlesAuthorization;

    public function read(User $user, Template $item, $role)
    {
        switch ($role) {
            case 'admin':
                $can = $user->admin->getPermission('template_read') != 'no';
                break;
            case 'customer':
                $can = $user->customer->id == $item->customer_id || !isset($item->customer_id);
                break;
        }

        return $can;
    }

    public function create(User $user, $role)
    {
        switch ($role) {
            case 'admin':
                $can = $user->admin->getPermission('template_create') == 'yes';
                break;
            case 'customer':
                $can = true;
                break;
        }

        return $can;
    }

    public function view(User $user, Template $item, $role)
    {
        switch ($role) {
            case 'admin':
                $ability = $user->admin->getPermission('template_read');
                $can = $ability == 'all'
                    || ($ability == 'own');
                break;
            case 'customer':
                $can = $user->customer->id == $item->customer_id || !isset($item->customer_id);
                break;
        }

        return $can;
    }

    public function update(User $user, Template $item, $role)
    {
        switch ($role) {
            case 'admin':
                $ability = $user->admin->getPermission('template_update');
                $can = $ability == 'all'
                    || ($ability == 'own');
                break;
            case 'customer':
                $can = $user->customer->id == $item->customer_id;
                break;
        }

        return $can;
    }

    public function image(User $user, Template $item, $role)
    {
        switch ($role) {
            case 'admin':
                $ability = $user->admin->getPermission('template_read');
                $can = $ability == 'all'
                    || ($ability == 'own');
                break;
            case 'customer':
                $can = $user->customer->id == $item->customer_id || !isset($item->customer_id);
                break;
        }

        return $can;
    }

    public function delete(User $user, Template $item, $role)
    {
        switch ($role) {
            case 'admin':
                $ability = $user->admin->getPermission('template_delete');
                $can = $ability == 'all'
                    || ($ability == 'own');
                break;
            case 'customer':
                $can = $user->customer->id == $item->customer_id;
                break;
        }

        return $can;
    }

    public function preview(User $user, Template $item, $role)
    {
        switch ($role) {
            case 'admin':
                $ability = $user->admin->getPermission('template_read');
                $can = $ability == 'all'
                    || ($ability == 'own');
                break;
            case 'customer':
                $can = $user->customer->id == $item->customer_id || !isset($item->customer_id);
                break;
        }

        return $can;
    }

    public function copy(User $user, Template $item, $role)
    {
        switch ($role) {
            case 'admin':
                $ability = $user->admin->getPermission('template_update');
                $can = $ability == 'all'
                    || ($ability == 'own');
                break;
            case 'customer':
                $can = $user->customer->id == $item->customer_id || !isset($item->customer_id);
                break;
        }

        return $can;
    }
}
