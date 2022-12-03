<?php

namespace Acelle\Policies;

use Acelle\Model\User;
use Acelle\Model\Sender;
use Illuminate\Auth\Access\HandlesAuthorization;

class SenderPolicy
{
    use HandlesAuthorization;

    public function listing(User $user, Sender $sender)
    {
        return true;
    }

    public function read(User $user, Sender $sender)
    {
        return $user->customer->id == $sender->customer_id;
    }

    public function create(User $user, Sender $sender)
    {
        return true;
    }

    public function update(User $user, Sender $sender)
    {
        return $user->customer->id == $sender->customer_id;
    }

    public function delete(User $user, Sender $sender)
    {
        return $user->customer->id == $sender->customer_id;
    }

    public function verify(User $user, Sender $sender)
    {
        return $user->customer->id == $sender->customer_id;
    }
}
