<?php

/**
 * FeedbackLoopHandler class.
 *
 * Model class for feedback loop handler
 *
 * LICENSE: This product includes software developed at
 * the Acelle Co., Ltd. (http://acellemail.com/).
 *
 * @category   MVC Model
 *
 * @author     N. Pham <n.pham@acellemail.com>
 * @author     L. Pham <l.pham@acellemail.com>
 * @copyright  Acelle Co., Ltd
 * @license    Acelle Co., Ltd
 *
 * @version    1.0
 *
 * @link       http://acellemail.com
 */

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Acelle\Library\StringHelper;
use Acelle\Library\Log as MailLog;

class Reply extends Model
{
    /**
     * Associations.
     *
     * @var object | collect
     */
    public function trackingLog()
    {
        return $this->belongsTo('Acelle\Model\TrackingLog', 'message_id', 'message_id');
    }

    public static function run()
    {
        try {
            MailLog::warning('Checking for reply...');

            // Connect to IMAP server
            $imapPath = "{{$this->host}:{$this->port}/{$this->protocol}/{$this->encryption}}INBOX";

            // try to connect
            $inbox = @imap_open($imapPath, $this->username, $this->password);

            // try again with ssl/novalidate-cert
            if ($inbox == false) {
                if (strcasecmp($this->encryption, 'ssl') == 0) {
                    MailLog::warning('Try using ssl/novalidate-cert instead');
                    $imapPath = "{{$this->host}:{$this->port}/{$this->protocol}/ssl/novalidate-cert}INBOX";
                    $inbox = @imap_open($imapPath, $this->username, $this->password);
                }
            }

            // if failed
            if ($inbox == false) {
                throw new \Exception("Cannot connect to the server: $imapPath");
            }

            // search and get unseen emails, function will return email ids
            $emails = imap_search($inbox, 'UNSEEN');

            if (!empty($emails)) {
                foreach ($emails as $message) {
                    self::processMessage($inbox, $message);
                }
            }

            // colse the connection
            imap_expunge($inbox);
            imap_close($inbox);
        } catch (\Exception $e) {
            // suppress the IMAP error
            // see http://stackoverflow.com/questions/5422405/cant-silence-imap-open-error-notices-in-php
            imap_errors();
            imap_alerts();
            MailLog::error('Cannot connect to reply handler '.$e->getMessage());
        }
    }

    /**
     * Process feedback message, extract the feedback information (comply to RFC).
     *
     * @return mixed
     */
    public function processMessage($mbox, $msgNo)
    {
        try {
            $header = imap_fetchheader($mbox, $msgNo);
            $body = imap_body($mbox, $msgNo, FT_PEEK);
            $msgId = $this->getMessageId($body);

            if (empty($msgId)) {
                imap_setflag_full($mbox, $msgNo, '\\Seen \\Flagged');
                throw new \Exception('Cannot find Message-ID, skipped');
            }

            Log::info('Processing reply detection for message '.$msgId);

            $trackingLog = TrackingLog::where('message_id', $msgId)->first();

            if (empty($trackingLog)) {
                imap_setflag_full($mbox, $msgNo, '\\Seen \\Flagged');
                throw new \Exception('message-id not found in tracking_logs, skipped');
            }

            // record a bounce log, one message may have more than one
            $reply = new self();
            $reply->message_id = $msgId;
            $reply->content = $header.PHP_EOL.$body;
            $reply->save();

            // just delete
            // imap_delete($mbox, $msgNo);
            // flag as seen
            imap_setflag_full($mbox, $msgNo, '\\Seen \\Flagged');

            Log::info('Feedback recorded for message '.$msgId);
        } catch (\Exception $ex) {
            Log::warning($ex->getMessage());
        }
    }

    /**
     * Extract message ID from email .
     *
     * @return string
     */
    public function getMessageId($message)
    {
        preg_match('/(?<=X-Acelle-Message-Id:)\s{0,1}<{0,1}(?<id>[a-zA-Z0-9\.]+[a-zA-Z0-9]+@[a-zA-Z0-9\.\-]+[a-zA-Z0-9]+)/', $message, $matched);
        if (array_key_exists('id', $matched)) {
            return StringHelper::cleanupMessageId($matched['id']);
        }

        // more tolerant matching (case-insensitive, no need for Acelle prefix, etc.)
        preg_match('/(?<=Message-Id:)\s{0,1}<{0,1}(?<id>[a-zA-Z0-9\.]+[a-zA-Z0-9]+@[a-zA-Z0-9\.\-]+[a-zA-Z0-9]+)/i', $message, $matched);
        if (array_key_exists('id', $matched)) {
            return StringHelper::cleanupMessageId($matched['id']);
        }

        return;
    }
}
