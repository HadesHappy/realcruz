<?php

namespace Acelle\Library\Facades;

use Illuminate\Support\Facades\Facade;
use Acelle\Library\HookManager;

class Hook extends Facade
{
    protected static function getFacadeAccessor()
    {
        return HookManager::class;
    }
}
