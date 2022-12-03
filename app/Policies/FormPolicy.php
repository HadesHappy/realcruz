<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Acelle\Model\User;
use Acelle\Model\Form;

class FormPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return true;
    }

    public function read(User $user, Form $form)
    {
        return $user->customer->id == $form->customer_id;
    }

    public function update(User $user, Form $form)
    {
        return $user->customer->id == $form->customer_id;
    }

    public function delete(User $user, Form $form)
    {
        return $user->customer->id == $form->customer_id;
    }

    public function publish(User $user, Form $form)
    {
        return $user->customer->id == $form->customer_id && $form->status == Form::STATUS_DRAFT;
    }

    public function unpublish(User $user, Form $form)
    {
        return $user->customer->id == $form->customer_id && $form->status == Form::STATUS_PUBLISHED;
    }
}
