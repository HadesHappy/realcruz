<?php

namespace Acelle\Library;

use File;
use Carbon\Carbon;
use Exception;
use Acelle\Library\Exception\QuotaExceeded;
use Closure;

class QuotaTracker
{
    protected $filepath;
    protected $mode = 'minute'; // hour, day, month, year
    protected $seperator = ':';
    protected $blockFormat = [
        'minute' => 'YmdHi',
        'hour' => 'YmdH00',
        'day' => 'Ymd0000',
        'month' => 'Ym000000',
        'year' => 'Y00000000',
    ];

    protected $quotaExceededCallback;

    public function __construct(string $filepath)
    {
        $this->filepath = $filepath;
        $this->createStorageFile();
    }

    public function whenQuotaExceeded(Closure $callback)
    {
        $this->quotaExceededCallback = $callback;
        return $this;
    }

    public function count(Carbon $now = null, array $limits = [], Closure $callback = null)
    {
        $lock = new Lockable($this->filepath);
        $lock->getExclusiveLock(function ($fopen) use ($now, $limits, $callback) {
            $now = $now ?: Carbon::now();

            // Throw an exception if test fails (quota exceeded)
            $this->test($now, $limits, $fopen);

            // Execute callback beore writing
            if (!is_null($callback)) {
                $callback();
            }

            // Record credits use
            $this->record($now, $fopen);
        }, $timeout = 15);
    }

    private function createStorageFile()
    {
        if (!file_exists($this->filepath)) {
            touch($this->filepath);
        }
    }

    private function test(Carbon $now, array $limits, $fopen)
    {
        // [
        //     [
        //         'name' => 'Emails per minute',
        //         'period_unit' => 'minute'
        //         'period_value' => '1'
        //         'limit' => 10,
        //     ], [
        //         'name' => 'Emails per 12 hours',
        //         'period_unit' => 'hour',
        //         'period_value' => '12',
        //         'limit' => 2000
        //     ], [
        //         ....
        //     ]
        // ]
        //

        foreach ($limits as $limit) {
            $period = sprintf("%s %s", $limit['period_value'], $limit['period_unit']);
            $fromDatetime = $now->copy()->subtract($period);

            $creditsUsed = $this->getCreditsUsed($fromDatetime, $now, $fopen);

            if ($creditsUsed >= $limit['limit']) {
                if (!is_null($this->quotaExceededCallback)) {
                    $quotaExceededCallback = $this->quotaExceededCallback;
                    $quotaExceededCallback($limit, $creditsUsed, $now);
                }
                throw new QuotaExceeded(sprintf("%s exceeded! %s/%s used", $limit['name'], $creditsUsed, $limit['limit']));
            }
        }
    }

    private function record(Carbon $now, $fopen)
    {
        $currentBlock = $this->makeBlock($now); // create block for the current date/time
        list($lastBlock, $count) = $this->parseLastRecord($fopen);

        // EMPTY() is safer than IS_NULL()
        if ($currentBlock == $lastBlock) {
            $record = $this->buildRecord($lastBlock, $count + 1);
            $this->updateRecord($record, $fopen);
        } else {
            $record = $this->buildRecord($currentBlock, $count = 1);
            $this->addRecord($record, $fopen);
        }
    }

    private function parseLastRecord($fopen)
    {
        $lastRecord = $this->getLastRecord($fopen);
        return $this->parseBlock($lastRecord);
    }

    private function parseBlock(string $record)
    {
        if (empty($record)) {
            return null;
        }

        return explode($this->seperator, $record);
    }

    public function buildRecord($block, $count)
    {
        return "{$block}{$this->seperator}{$count}";
    }

    // Convert the provided datetime $now to a string
    public function makeBlock($now)
    {
        $now = $now ?: Carbon::now();
        $format = $this->blockFormat[$this->mode];
        return $now->format($format);
    }

    private function getLastRecord($fopen)
    {
        // Find offline
        fseek($fopen, 0, SEEK_END);
        $offset = ftell($fopen) - 1; // Offset values from: -1, 0, 1, 2...

        if ($offset < 0) {
            return ""; // File empty
        }

        fseek($fopen, $offset--); //seek to the end of the line

        // Ignore consecutive empty newlines
        $char = fgetc($fopen);
        while ($offset >= 0 && ($char === "\n")) {
            fseek($fopen, $offset--);
            $char = fgetc($fopen);
        }

        if ($offset < 0) {
            fseek($fopen, 0);
            return trim(fgets($fopen)); // the whole file has Zero or One character (except \n)
        }

        // Continue with offset $offset;
        fseek($fopen, $offset--);
        $char = fgetc($fopen);
        while ($offset >= 0 && $char != "\n") {
            fseek($fopen, $offset--);
            $char = fgetc($fopen);
        }

        if ($offset < 0) { // get to the beginning of file
            fseek($fopen, 0);
        }

        $lastLine = fgets($fopen);
        return trim($lastLine);
    }

    public function updateRecord(string $record, $fopen)
    {
        fseek($fopen, 0, SEEK_END);
        $offset = ftell($fopen) - 1; // Offset values from: -1, 0, 1, 2...

        if ($offset < 0) {
            return ""; // File empty
        }

        fseek($fopen, $offset--); //seek to the end of the line

        // Ignore consecutive empty newlines
        $char = fgetc($fopen);
        while ($offset >= 0 && ($char === "\n")) {
            fseek($fopen, $offset--);
            $char = fgetc($fopen);
        }

        if ($offset < 0) {
            fseek($fopen, 0); // either a leading newline or leading newline + 1char, overwrite leading "\nX" if any
        }

        // Continue with offset $offset;
        fseek($fopen, $offset);
        $char = fgetc($fopen);
        while ($offset >= 0 && $char != "\n") {
            $offset -= 1;
            fseek($fopen, $offset);
            $char = fgetc($fopen);
        }

        if ($offset < 0) { // get to the beginning of file
            fseek($fopen, 0);
        }

        fwrite($fopen, $record);
    }

    public function truncate()
    {
        $fopen = fopen($this->filepath, 'r+');
        fseek($fopen, 0, SEEK_END);
        $offset = ftell($fopen) - 1; // Offset values from: -1, 0, 1, 2...

        if ($offset < 0) {
            return; // File empty
        }

        fseek($fopen, $offset); //seek to the end of the line
        $char = fgetc($fopen);
        while ($offset > 0 && ($char === "\n")) {
            $offset -= 1;
            fseek($fopen, $offset);
            $char = fgetc($fopen);
        }

        ftruncate($fopen, ++$offset);
        fclose($fopen);
    }

    public function addRecord(string $record, $fopen)
    {
        fseek($fopen, 0, SEEK_END);
        $offset = ftell($fopen) - 1; // Offset values from: -1, 0, 1, 2...

        if ($offset < 0) {
            fwrite($fopen, $record);
        } else {
            fseek($fopen, $offset); //seek to the end of the line
            $char = fgetc($fopen);
            while ($offset > 0 && ($char === "\n")) {
                $offset -= 1;
                fseek($fopen, $offset);
                $char = fgetc($fopen);
            }

            fseek($fopen, ++$offset);
            fwrite($fopen, "\n".$record);
        }
    }

    public function getRecords(Carbon $fromDatetime = null, Carbon $toDatetime = null, $fopen = null)
    {
        $fromDatetime = $fromDatetime ?: Carbon::createFromTimestamp(0); // Create the earliest date of 1970-01-01
        $toDatetime = $toDatetime ?: Carbon::now(); // Current date

        $fromDatetimeStr = $this->makeBlock($fromDatetime);
        $toDatetimeStr = $this->makeBlock($toDatetime);

        $records = [];

        if (is_null($fopen)) {
            $fopen = fopen($this->filepath, 'r');
            $closeFile = true;
        } else {
            rewind($fopen);
            $closeFile = false;
        }

        rewind($fopen);
        while (!feof($fopen)) {
            $record = trim(fgets($fopen));

            if (empty($record)) {
                break;
            }

            list($block, $count) = $this->parseBlock($record);

            if (empty($block)) {
                throw new Exception("Invalid block {$record}");
            }

            if ($block > $fromDatetimeStr && $block <= $toDatetimeStr) {
                $records[] = [$block, $count];
            }
        }

        if ($closeFile) {
            fclose($fopen);
        }

        // Return
        return $records;
    }

    public function getCreditsUsed(Carbon $fromDatetime = null, Carbon $toDatetime = null, $fopen = null)
    {
        $records = $this->getRecords($fromDatetime, $toDatetime, $fopen);
        $counts = array_map(function ($record) {
            list($block, $count) = $record;
            return $count;
        }, $records);

        $total = array_sum($counts);
        return $total;
    }
}
