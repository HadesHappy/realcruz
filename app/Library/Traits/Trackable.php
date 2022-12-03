<?php

namespace Acelle\Library\Traits;

use Illuminate\Contracts\Bus\Dispatcher;
use Exception;
use Acelle\Model\JobMonitor;

trait Trackable
{
    public $monitor;
    public $eventAfterDispatched;
    public $eventAfterFinished;

    public function setMonitor(JobMonitor $monitor)
    {
        $this->monitor = $monitor;
    }

    public function afterDispatched($callback)
    {
        $this->eventAfterDispatched = $callback;
    }

    public function afterFinished($callback)
    {
        $this->eventAfterFinished = $callback;
    }
}
