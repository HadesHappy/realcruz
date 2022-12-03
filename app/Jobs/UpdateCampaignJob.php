<?php

namespace Acelle\Jobs;

use Illuminate\Contracts\Queue\ShouldBeUnique;

class UpdateCampaignJob extends Base implements ShouldBeUnique
{
    protected $campaign;
    public $uniqueFor = 3600;

    public function __construct($campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->campaign->updateCache();
    }

    public function uniqueId()
    {
        return $this->campaign->id;
    }
}
