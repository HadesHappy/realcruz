<?php

namespace Acelle\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Acelle\Model\Campaign;
use Illuminate\Support\Carbon;
use Acelle\Library\Traits\Trackable;

class ScheduleCampaign implements ShouldQueue
{
    use Trackable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $campaign;

    public $timeout = 300;
    public $failOnTimeout = true;
    public $tries = 1;
    public $maxExceptions = 1;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Campaign $campaign)
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
        if ($this->campaign->isPaused()) {
            return;
        }

        try {
            $this->campaign->logger()->info("Launch campaign ---------------------->");
            $this->campaign->launch();
        } catch (\Throwable $e) {
            $errorMsg = "Error scheduling campaign: ".$e->getMessage()."\n".$e->getTraceAsString();
            $this->campaign->setError($errorMsg);

            // To set the job to failed
            throw $e;
        }
    }
}
