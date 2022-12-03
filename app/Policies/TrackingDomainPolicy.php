<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Acelle\Model\User;
use Acelle\Model\TrackingDomain;
use Acelle\Model\Plan;

class TrackingDomainPolicy
{
    use HandlesAuthorization;

    public function read(User $user, TrackingDomain $item, $role)
    {
        return true;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, TrackingDomain $item, $role)
    {
        return $user->customer->id == $item->customer_id;
    }

    public function delete(User $user, TrackingDomain $item, $role)
    {
        return $user->customer->id == $item->customer_id;
    }
}
