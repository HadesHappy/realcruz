<?php

namespace Acelle\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessing;
use Acelle\Library\Log as MailLog;
use Queue;

class JobServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // IMPORTANT:
        // ONLY TRIGGER QUEUE EVENTS FOR JOB_MONITORS THAT DO NOT HAVE BATCH
        // IT IS BECAUSE ONES WIH A BATCH WILL BE UPDATED BY BATCH EVENTS, EXCEPT FOR "BEFORE"
        // Initialize the MailLog which writes logs to mail.log
        $this->initMailLog();

        Queue::before(function (JobProcessing $event) {
            $job = $this->getJobObject($event);
            if (property_exists($job, 'monitor')) {
                // 'before' events should be applied to both JOB and BATCH monitor
                $monitor = $job->monitor;
                $monitor->setRunning();
            }
        });

        Queue::after(function (JobProcessed $event) {
            $job = $this->getJobObject($event);
            if (property_exists($job, 'monitor')) {
                $monitor = $job->monitor;
                if (is_null($monitor->batch_id)) {
                    $monitor->setDone();
                }
            }
        });

        Queue::failing(function (JobFailed $event) {
            $job = $this->getJobObject($event);
            if (property_exists($job, 'monitor')) {
                $monitor = $job->monitor;
                if (is_null($monitor->batch_id)) {
                    $monitor->setFailed($event->exception);
                }
            }
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        //
    }

    /**
     * Register the application services.
     */
    private function getJobObject($event)
    {
        $data = $event->job->payload();

        return unserialize($data['data']['command']);
    }

    /**
     * Init the MailLog.
     */
    private function initMailLog()
    {
        MailLog::configure(storage_path().'/logs/' . php_sapi_name() . '/mail.log');
    }
}
