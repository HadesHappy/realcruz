<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;

class SubscriptionsEmailVerificationServer extends Model
{
    public function emailVerificationServer()
    {
        return $this->belongsTo('Acelle\Model\EmailVerificationServer', 'server_id');
    }
}
