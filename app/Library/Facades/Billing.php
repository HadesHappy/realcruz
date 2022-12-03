<?php

namespace Acelle\Library\Facades;

use Illuminate\Support\Facades\Facade;
use Acelle\Library\BillingManager;

class Billing extends Facade
{
    protected static function getFacadeAccessor()
    {
        return BillingManager::class;
    }
}
