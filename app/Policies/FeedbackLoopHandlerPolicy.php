<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Acelle\Model\User;
use Acelle\Model\FeedbackLoopHandler;

class FeedbackLoopHandlerPolicy
{
    use HandlesAuthorization;

    public function read(User $user, FeedbackLoopHandler $item)
    {
        $ability = $user->admin->getPermission('fbl_handler_read');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->admin->id == $item->admin_id);

        return $can;
    }

    public function readAll(User $user, FeedbackLoopHandler $item)
    {
        $can = $user->admin->getPermission('fbl_handler_read') == 'all';

        return $can;
    }

    public function create(User $user, FeedbackLoopHandler $item)
    {
        $can = $user->admin->getPermission('fbl_handler_create') == 'yes';

        return $can;
    }

    public function update(User $user, FeedbackLoopHandler $item)
    {
        $ability = $user->admin->getPermission('fbl_handler_update');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->admin->id == $item->admin_id);

        return $can;
    }

    public function delete(User $user, FeedbackLoopHandler $item)
    {
        $ability = $user->admin->getPermission('fbl_handler_delete');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->admin->id == $item->admin_id);

        return $can;
    }

    public function test(User $user, FeedbackLoopHandler $item)
    {
        return $this->update($user, $item) || !isset($item->id);
    }
}
