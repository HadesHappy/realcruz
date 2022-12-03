<?php

namespace Acelle\Jobs;

use Acelle\Library\Traits\Trackable;

class ExportCampaignLog extends Base
{
    use Trackable;

    public $timeout = 3600;

    protected $campaign;
    protected $logtype;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($campaign, $logtype)
    {
        $this->campaign = $campaign;
        $this->logtype = $logtype;

        // Set the initial value for progress check
        $this->afterDispatched(function ($thisJob, $monitor) {
            $monitor->setJsonData([
                'percentage' => 0
            ]);
        });
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->campaign->generateTrackingLogCsv($this->logtype, function ($percentage, $path) {
            $this->monitor->updateJsonData([
                'percentage' => $percentage,
                'path' => $path,
            ]);
        });
    }
}
