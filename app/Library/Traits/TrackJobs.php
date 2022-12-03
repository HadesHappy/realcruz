<?php

namespace Acelle\Library\Traits;

use Illuminate\Contracts\Bus\Dispatcher;
use Acelle\Model\JobMonitor;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;
use Exception;
use DB;

trait TrackJobs
{
    // Currently, only one monitor per campaign (soft business)
    public function jobMonitors()
    {
        return $this->hasMany(JobMonitor::class, 'subject_id')->where('subject_name', self::class);
    }

    // DO NOT USE DB TRANSACTION
    // OTHERWISE BATCH_ID OR JOB_ID MAY NOT BE AVAILABLE
    public function dispatchWithMonitor($job)
    {
        $jobType = get_class($job);
        $monitor = JobMonitor::makeInstance($this, $jobType); // QUEUED status

        // actually save
        $monitor->save();
        $job->setMonitor($monitor);

        // Store the closures (for executing after dispatched) to a temporary place
        // It is because Jobs are not allowed to store closures (not serializable)
        $events = [
            $job->eventAfterDispatched
        ];

        // Destroy closure attributes which cannot be serialized
        $job->eventAfterDispatched = null; // Destroy the closure

        // Actually dispatch
        $dispatchedJobId = app(Dispatcher::class)->dispatch($job);

        // Associate job ID with monitor
        $monitor->job_id = $dispatchedJobId;
        $monitor->save();

        // Execute job's callback
        foreach ($events as $closure) {
            if (!is_null($closure)) {
                $closure($job, $monitor);
            }
        }

        // Return
        return $monitor;
    }

    // IMPORTANT: this is normally for jobs that create other jobs
    public function dispatchWithBatchMonitor($job, $thenCallback, $catchCallback, $finallyCallback)
    {
        // IMPORTANT:
        // UPdate QUEUE events in order NOT to set AFTER / FAILING... for job used in a batch (only BEFORE event is OK);
        if (!property_exists($job, 'monitor')) {
            throw new Exception(sprintf('Job class `%s` should use `Trackable` trait in order to use $eventAfterDispatched callback', get_class($job)));
        }

        // Create job monitor record
        $monitor = JobMonitor::makeInstance($this, get_class($job));
        $monitor->save();

        // Set job monitor
        $job->setMonitor($monitor);

        // Store the closures (for executing after dispatched) to a temporary place
        // It is because Jobs are not allowed to store closures (not serializable)
        $events = [
            'afterDispatched' => $job->eventAfterDispatched,
            'afterFinished' => $job->eventAfterFinished,
        ];

        // Destroy closure attributes which cannot be serialized
        $job->eventAfterDispatched = null; // Destroy the closure
        $job->eventAfterFinished = null;

        $batch = Bus::batch($job)->then(function (Batch $batch) use ($monitor, $thenCallback) {
            // Finish successfully
            $monitor->setDone();

            if (!is_null($thenCallback)) {
                $thenCallback($batch);
            }
        })->catch(function (Batch $batch, \Throwable $e) use ($monitor, $catchCallback) {
            // Failed and finish
            $monitor->setFailed($e);

            if (!is_null($catchCallback)) {
                $catchCallback($batch, $e);
            }
        })->finally(function (Batch $batch) use ($monitor, $finallyCallback, $events, $job) {
            if (!is_null($finallyCallback)) {
                $finallyCallback($batch);
            }

            // Execute job's callback
            if (array_key_exists('afterFinished', $events)) {
                $closure = $events['afterFinished'];
                if (!is_null($closure)) {
                    $closure($job, $monitor);
                }
            }
        })->onQueue('batch')->dispatch();

        $monitor->batch_id = $batch->id;
        $monitor->save();

        // Execute job's callback
        if (array_key_exists('afterDispatched', $events)) {
            $closure = $events['afterDispatched'];
            if (!is_null($closure)) {
                $closure($job, $monitor);
            }
        }

        // Return
        return $monitor;
    }
}
