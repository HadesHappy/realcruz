<?php

namespace Acelle\Events;

use Acelle\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AdminLoggedIn extends Event
{
    use SerializesModels;

    protected $admin;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($admin = null)
    {
        $this->admin = $admin;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
