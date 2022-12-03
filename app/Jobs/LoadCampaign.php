<?php

namespace Acelle\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Acelle\Model\Campaign;
use Acelle\Model\Subscriber;
use Illuminate\Support\Carbon;
use Acelle\Library\Traits\Trackable;

class LoadCampaign implements ShouldQueue
{
    use Trackable;
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $timeout = 7200;
    public $failOnTimeout = true;
    public $tries = 1;
    public $maxExceptions = 1;

    protected $campaign;

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
        if ($this->batch()->cancelled()) {
            return;
        }

        // Clear any HTML cache
        $this->campaign->clearCache();

        // Last chance for campaign to setup
        // @todo Find a better place for the following method, afterSave for example
        $this->campaign->updateLinks();

        // Update status
        $this->campaign->setSending();

        // Iterating through a big list ans create job objects may cause memory leak
        // So, LoadCampaign only loads a certain numbers subscribers each time, then just finish job to release the queue listener (remember to configure the queue correctly to release after a short time)
        // When a campaign is done, it will automaticall launch a new LoadCampaign job if there are more subscribers to send
        $loadLimit = 100 + rand(1, 9);
        $this->campaign->logger()->info(sprintf('Loading %s contacts to shoot', $loadLimit));

        // Iterate through contacts and launch sending process
        $this->campaign->prepare(function ($campaign, $subscriber, $server) {
            $this->batch()->add(new SendMessage($campaign, $subscriber, $server));
        }, $loadLimit);
    }
}
