<?php

/**
 * Lockable class.
 *
 * Support concorrency-enabled classes
 *
 * LICENSE: This product includes software developed at
 * the Acelle Co., Ltd. (http://acellemail.com/).
 *
 * @category   Acelle Library
 *
 * @author     N. Pham <n.pham@acellemail.com>
 * @author     L. Pham <l.pham@acellemail.com>
 * @copyright  Acelle Co., Ltd
 * @license    Acelle Co., Ltd
 *
 * @version    1.0
 *
 * @link       http://acellemail.com
 *
 * @todo separate the time-series and the quota stuffs
 */

namespace Acelle\Library;

class Lockable
{
    /**
     * Get exclusive lock.
     */
    private $file;

    public function __construct($file)
    {
        if (!file_exists($file)) {
            touch($file);
        }

        $this->file = $file;
    }

    /**
     * Get exclusive lock.
     */
    public function getExclusiveLock($callback, $timeout = 15, $timeoutCallback = null)
    {
        $start = time();
        $reader = fopen($this->file, 'r+');
        try {
            while (true) {
                // raise an exception and quit if timed out
                if ($this->isTimeout($start, $timeout)) {
                    if (is_null($timeoutCallback)) {
                        throw new \Exception('Timeout getting lock #Lockable for: '.$this->file);
                    } else {
                        $timeoutCallback();
                        break;
                    }
                }

                if (flock($reader, LOCK_EX | LOCK_NB)) {  // acquire an exclusive lock
                    // execute the callback
                    $callback($reader);
                    break;
                }
            }
        } finally {
            fflush($reader);
            flock($reader, LOCK_UN);    // release the lock
            fclose($reader);
        }
    }

    /**
     * Get shared lock.
     */
    public function getSharedLock($callback, $timeout = 5, $timeoutCallback = null)
    {
        $start = time();
        $reader = fopen($this->file, 'r');
        while (true) {
            // raise an exception and quit if timed out
            if ($this->isTimeout($start, $timeout)) {
                if (is_null($timeoutCallback)) {
                    throw new \Exception('Timeout getting lock #Lockable for: '.$this->file);
                } else {
                    $timeoutCallback();
                    break;
                }
            }

            if (flock($reader, LOCK_SH | LOCK_NB)) {  // acquire an exclusive lock
                // execute the callback
                $callback($this);

                flock($reader, LOCK_UN);    // release the lock
                fclose($reader);
                break;
            }
        }
    }

    /**
     * Check for timeout.
     */
    public function isTimeout($startTime, $timeoutDuration)
    {
        return (time() - $startTime > $timeoutDuration);
    }
}
