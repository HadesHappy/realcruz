<?php

namespace Acelle\Jobs;

use Acelle\Model\MailList;
use Exception;
use Acelle\Library\Traits\Trackable;
use Illuminate\Bus\Batchable;

class VerifyMailListJob extends Base
{
    use Batchable;
    use Trackable;

    public $timeout = 14400;

    protected $mailList;
    protected $server;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mailList, $server)
    {
        $this->mailList = $mailList;
        $this->server = $server;

        $this->afterDispatched(function ($thisJob, $monitor) {
            $monitor->setJsonData([
                'percentage' => 0,
                'total' => 0,
                'processed' => 0,
                'failed' => 0,
                'message' => 'Verification process is being queued...',
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
        if ($this->batch()->cancelled()) {
            return;
        }

        $this->monitor->updateJsonData([
            'message' => 'Verification is in progress...',
        ]);

        // Get subscribers that are not verified
        $query = $this->mailList->subscribers()->unverified();

        if (!$query->exists()) {
            throw new Exception('There is no unverified contact in your list');
        }

        $this->monitor->updateJsonData([
            'message' => "Verification is in progress ({$query->count()})...",
        ]);

        // Query batches of 1000 records each, dispatch the verification job
        // Add job to batch
        cursorIterate($query, 'subscribers.id', $size = 1000, function ($subscribers, $page) {
            foreach ($subscribers as $subscriber) {
                $this->batch()->add(new VerifySubscriber($subscriber, $this->server));
            }
        });
    }
}
