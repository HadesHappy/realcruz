<?php

namespace Acelle\Listeners;

use Acelle\Events\CampaignUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Acelle\Jobs\UpdateCampaignJob;

class CampaignUpdatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CampaignUpdated  $event
     * @return void
     */
    public function handle(CampaignUpdated $event)
    {
        if ($event->delayed) {
            dispatch(new UpdateCampaignJob($event->campaign));
        } else {
            // @deprecated
            $event->campaign->updateCache();
        }
    }
}
