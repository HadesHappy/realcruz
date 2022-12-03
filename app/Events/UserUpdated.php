<?php

namespace Acelle\Events;

use Acelle\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserUpdated extends Event
{
    use SerializesModels;

    public $customer;
    public $delayed;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($customer, $delayed = true)
    {
        $this->customer = $customer;
        $this->delayed = $delayed;
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
