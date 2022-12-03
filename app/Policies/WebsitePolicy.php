<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Acelle\Model\User;
use Acelle\Model\Website;

class WebsitePolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return true;
    }

    public function read(User $user, Website $website)
    {
        return $user->customer->id == $website->customer_id;
    }

    public function update(User $user, Website $website)
    {
        return $user->customer->id == $website->customer_id;
    }

    public function delete(User $user, Website $website)
    {
        return $user->customer->id == $website->customer_id;
    }

    public function connect(User $user, Website $website)
    {
        return $user->customer->id == $website->customer_id && $website->status == Website::STATUS_INACTIVE;
    }

    public function disconnect(User $user, Website $website)
    {
        return $user->customer->id == $website->customer_id && $website->status == Website::STATUS_CONNECTED;
    }
}
