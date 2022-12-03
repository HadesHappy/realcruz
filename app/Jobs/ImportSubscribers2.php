<?php

namespace Acelle\Jobs;

use Illuminate\Bus\Batchable;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use Exception;
use Acelle\Library\Traits\Trackable;

class ImportSubscribers2 extends Base
{
    use Batchable;
    use Trackable;

    public $timeout = 7200;

    protected $list;
    protected $file; // Example: /home/acelle/storage/app/tmp/import-000000.csv

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($list, $file)
    {
        $this->list = $list;
        $this->file = $file;

        // Set the initial value which must be available for periodical progress checks
        // Important: only the first log should use setJsonData
        //            other calls should use update....
        $this->afterDispatched(function ($thisJob, $monitor) {
            $monitor->setJsonData([
                'message' => trans('messages.list.import.progress.queued'),
            ]);
        });

        // After the batch has finished
        // This is the place for the job itself to log
        $this->afterFinished(function ($thisJob, $monitor) {
            // For example
            // $monitor->updateJsonData([
            //     'message' => 'Import complete!',
            // ]);
        });
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Use a logger to log failed
        $formatter = new LineFormatter("[%datetime%] %channel%.%level_name%: %message%\n");
        $logfile = $this->file.".log";
        $stream = new StreamHandler($logfile, Logger::DEBUG);
        $stream->setFormatter($formatter);

        $pid = getmypid();
        $logger = new Logger($pid);
        $logger->pushHandler($stream);

        $this->monitor->updateJsonData([
            'logfile' => $logfile,
            'message' => trans('messages.list.import.progress.running'),
        ]);

        // Write log, to make sure the file is created
        $logger->info(trans('messages.list.import.progress.running'));

        $this->list->parseCsvFile($this->file, function ($record) use ($logger) {
            $this->batch()->add(new VerifyAndCreateSubscriber($this->list, $record, $logger, $this->monitor));
        });
    }
}
