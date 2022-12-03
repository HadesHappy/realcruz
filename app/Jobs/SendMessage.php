<?php

namespace Acelle\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Acelle\Model\Subscriber;
use Acelle\Library\QuotaManager;
use Acelle\Library\Exception\NoCreditsLeft;
use Acelle\Library\Exception\QuotaExceeded;
use Exception;

class SendMessage implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $timeout = 600;
    public $maxExceptions = 1; // This is required if retryUntil is used, otherwise, the default value is 255
    public $failOnTimeout = true;

    // $tries is no longer needed (or effective) due to the retryUntil() method
    // public $tries = 1;

    protected $subscriber;
    protected $server;
    protected $campaign;
    protected $triggerId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($campaign, Subscriber $subscriber, $server, $triggerId = null)
    {
        $this->campaign = $campaign;
        $this->subscriber = $subscriber;
        $this->server = $server;
        $this->triggerId = $triggerId;
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
        // Remember that this job may not belong to a batch
        if ($this->batch() && $this->batch()->cancelled()) {
            return;
        }

        $this->send();
    }

    // Use a dedicated method with no dependency for easy testing
    public function send($exceptionCallback = null)
    {
        $logger = $this->campaign->logger();
        $email = $this->subscriber->getEmail();
        $subscription = $this->campaign->customer->activeSubscription();

        // Prepare the email message to send
        list($message, $msgId) = $this->campaign->prepareEmail($this->subscriber, $this->server, $fromCache = true);

        // Actually send!
        $logger->info(sprintf('Sending to %s [Server "%s"]', $email, $this->server->name));

        try {
            // Count related quota trackers
            // Important: sacrisfy server credits, put quota check for server before that of subscription
            // Otherwise, it may cost 1 subscription's credit (shown to user) every time the job is release back to queue
            // enforce() means: do not care about "credits", check "limits" only
            QuotaManager::with($this->server, 'send')->enforce();  // First
            QuotaManager::with($subscription, 'send')->enforce();  // Later

            // Actually send (or throw an exception)
            $sent = $this->server->send($message);

            // Log successful shot
            $this->campaign->trackMessage($sent, $this->subscriber, $this->server, $msgId, $this->triggerId);
            $logger->info(sprintf('Sent to %s [Server "%s"]', $email, $this->server->name));
        } catch (QuotaExceeded $ex) {
            if (!is_null($exceptionCallback)) {
                $exceptionCallback($ex);
            }
            // Releease the job, have it tried again later on, after 1 minutes
            $logger->warning(sprintf("Delay [%s] for 60 seconds: %s", $email, $ex->getMessage()));

            // Release the job, have it try again after 60 seconds and (hopefully) the quota limits will be lifted then as time goes by
            $this->release(60);
        } catch (Exception $ex) {
            if (!is_null($exceptionCallback)) {
                $exceptionCallback($ex);
            }
            $message = sprintf("Error sending to [%s]. Error: %s", $email, $ex->getMessage());
            $logger->error($message);
            throw new Exception($message);
        }
    }
}
