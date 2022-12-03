<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Acelle\Library\Traits\HasUid;
use Acelle\Library\Lockable;
use Exception;

class JobMonitor extends Model
{
    use HasFactory;
    use HasUid;

    public const STATUS_QUEUED = 'queued';
    public const STATUS_RUNNING = 'running';
    public const STATUS_DONE = 'done';
    public const STATUS_FAILED = 'failed';

    public static function makeInstance($subject, $jobType)
    {
        $monitor = new self();
        $monitor->status = self::STATUS_QUEUED;
        $monitor->subject_name = get_class($subject);
        $monitor->subject_id = $subject->id;
        $monitor->job_type = $jobType;

        // Return
        return $monitor;
    }

    public function scopeByJobType($query, $jobType)
    {
        return $query->where('job_type', $jobType);
    }

    public function getBatch()
    {
        if (is_null($this->batch_id)) {
            return;
        }

        return Bus::findBatch($this->batch_id);
    }

    public function withExclusiveLock($closure)
    {
        $lockFile = storage_path('app/lock-job-monitor-'.$this->uid);
        $lock = new Lockable($lockFile);
        $lock->getExclusiveLock(function ($lockReader) use ($closure) {
            $closure($this->refresh());
        }, $timeout = 60);
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function getJob()
    {
        return $this->job;
    }

    public function hasJob()
    {
        return ! is_null($this->job_id);
    }

    public function hasBatch()
    {
        return ! is_null($this->batch_id);
    }

    public function setFailed($exception)
    {
        $this->status = self::STATUS_FAILED;
        $errorMsg = "Error executing job. ".$exception->getMessage();
        $this->error = $errorMsg;
        $this->save();
    }

    public function setRunning()
    {
        $this->status = self::STATUS_RUNNING;
        $this->save();
    }

    public function setDone()
    {
        $this->status = self::STATUS_DONE;
        $this->save();
    }

    public function setQueued()
    {
        $this->status = self::STATUS_QUEUED;
        $this->save();
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_RUNNING, self::STATUS_QUEUED]);
    }

    public function getJsonData()
    {
        // Also convert null or empty string to an empty array ([])
        return json_decode($this->data, true) ?: [];
    }

    public function setJsonData($data)
    {
        $this->data = json_encode($data);
        $this->save();
    }

    public function updateJsonData($data)
    {
        $json = $this->getJsonData();
        $json = array_merge($json, $data);
        $this->setJsonData($json);
    }

    public function cancelWithoutDeleteBatch()
    {
        $this->cancelBatch();
    }

    public function cancel()
    {
        $this->cancelJob(); // if any
        $this->cancelBatch(); // if any

        // For now, do not store cancelled job
        // So, just delete the record
        $this->delete();
    }

    private function cancelJob()
    {
        // Get the job record in the `jobs` database table
        $job = $this->getJob();

        // Remove it from queue, if any
        if (!is_null($job)) {
            $job->delete();
        }
    }

    private function cancelBatch()
    {
        // Then get the batch
        // This is not the batch record in job_batches model
        // So we can just cancel it to have its remaining jobs perish!
        // It will be pruned with queue:prune-batches command
        $batch = $this->getBatch();
        if (!is_null($batch)) {
            $batch->cancel();
        }
    }
}
