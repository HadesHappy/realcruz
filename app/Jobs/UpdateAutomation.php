<?php

namespace Acelle\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Acelle\Model\Automation2;

class UpdateAutomation extends Base
{
    protected $automation;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($automation)
    {
        $this->automation = $automation;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->automation->updateCache();
    }
}
