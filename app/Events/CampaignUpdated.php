<?php

namespace Acelle\Events;

use Acelle\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CampaignUpdated extends Event
{
    use SerializesModels;

    public $campaign;
    public $delayed;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($campaign, $delayed = true)
    {
        $this->campaign = $campaign;
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
