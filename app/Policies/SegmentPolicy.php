<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Acelle\Model\User;
use Acelle\Model\Segment;

class SegmentPolicy
{
    use HandlesAuthorization;

    public function create(User $user, Segment $item)
    {
        $customer = $user->customer;
        $max_per_list = $customer->getOption('segment_per_list_max');

        return $customer->id == $item->mailList->customer_id
                && ($max_per_list > $item->mailList->segments()->count()
                || $max_per_list == -1);
    }

    public function update(User $user, Segment $item)
    {
        $customer = $user->customer;
        return $item->mailList->customer_id == $customer->id;
    }

    public function delete(User $user, Segment $item)
    {
        $customer = $user->customer;
        return $item->mailList->customer_id == $customer->id;
    }

    public function export(User $user, Segment $item)
    {
        $customer = $user->customer;
        return $item->mailList->customer_id == $customer->id;
    }
}
