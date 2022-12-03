<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Acelle\Model\User;
use Acelle\Model\Plugin;

class PluginPolicy
{
    use HandlesAuthorization;

    public function read(User $user, Plugin $plugin, $role)
    {
        switch ($role) {
            case 'admin':
                return true;
                break;
            case 'customer':
                return false;
                break;
        }
    }

    public function readAll(User $user, Plugin $plugin, $role)
    {
        switch ($role) {
            case 'admin':
                return true;
                break;
            case 'customer':
                return false;
                break;
        }
    }

    public function install(User $user, $role)
    {
        switch ($role) {
            case 'admin':
                return true;
                break;
            case 'customer':
                return false;
                break;
        }
    }

    public function update(User $user, Plugin $plugin, $role)
    {
        switch ($role) {
            case 'admin':
                return true;
                break;
            case 'customer':
                return false;
                break;
        }
    }

    public function delete(User $user, Plugin $plugin, $role)
    {
        switch ($role) {
            case 'admin':
                return true;
                break;
            case 'customer':
                return false;
                break;
        }
    }

    public function disable(User $user, Plugin $plugin, $role)
    {
        switch ($role) {
            case 'admin':
                return  $plugin->status != Plugin::STATUS_INACTIVE;;
                break;
            case 'customer':
                return false;
                break;
        }
    }

    public function enable(User $user, Plugin $plugin, $role)
    {
        switch ($role) {
            case 'admin':
                return  $plugin->status != Plugin::STATUS_ACTIVE;;
                break;
            case 'customer':
                return false;
                break;
        }
    }
}
