<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Acelle\Model\User;
use Acelle\Model\Layout;

class LayoutPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Layout $item)
    {
        $ability = $user->admin->getPermission('layout_update');
        $can = $ability == 'yes';

        return $can;
    }
}
