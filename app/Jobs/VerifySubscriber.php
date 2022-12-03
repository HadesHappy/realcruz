<?php

namespace Acelle\Jobs;

use Illuminate\Bus\Batchable;
use Acelle\Library\QuotaManager;
use Acelle\Library\Exception\NoCreditsLeft;
use Acelle\Library\Exception\QuotaExceeded;
use Acelle\Library\Exception\VerificationTakesLongerThanNormal;
use Exception;
use Closure;

class VerifySubscriber extends Base
{
    use Batchable;

    public $timeout = 120;
    public $maxExceptions = 1; // This is required if retryUntil is used, otherwise, the default value is 255
    public $failOnTimeout = true;

    protected $server;
    protected $subscriber;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($subscriber, $server)
    {
        $this->subscriber = $subscriber;
        $this->server = $server;
    }

    /**
     * Determine the time at which the job should timeout.
     *
     * @return \DateTime
     */
    public function retryUntil()
    {
        return now()->addHours(12);
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

        $this->doVerify();
    }

    public function doVerify(Closure $exceptionCallback = null)
    {
        $subscription = $this->subscriber->mailList->customer->activeSubscription();

        if (is_null($subscription)) {
            throw new Exception("User does not have an acive subscription");
        }

        try {
            // Count related quota trackers
            QuotaManager::with($subscription, 'verify')->enforce();
            QuotaManager::with($this->server, 'verify')->enforce();

            // Actually verify
            $this->subscriber->verify($this->server);
        } catch (VerificationTakesLongerThanNormal $ex) {
            // Just ignore and return
            // Warn user that there are certain subscribers that are skipped
            return;
        } catch (QuotaExceeded $ex) {
            if (!is_null($exceptionCallback)) {
                $exceptionCallback($ex);
            }

            // Release the job, have it try again after 60 seconds and (hopefully) the quota limits will be lifted then as time goes by
            $this->release(60);
        } catch (Exception $ex) {
            if (!is_null($exceptionCallback)) {
                $exceptionCallback($ex);
            }

            throw $ex;
        }
    }
}
