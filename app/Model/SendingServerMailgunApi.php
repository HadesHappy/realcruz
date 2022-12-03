<?php

/**
 * SendingServerMailgunApi class.
 *
 * Abstract class for Mailgun API sending server
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

use Acelle\Library\Log as MailLog;
use Acelle\Library\StringHelper;

class SendingServerMailgunApi extends SendingServerMailgun
{
    protected $table = 'sending_servers';

    /**
     * Send the provided message.
     *
     * @return bool
     *
     * @param message
     */
    public function send($message, $params = array())
    {
        $toEmail = array_keys($message->getTo())[0];
        $result = $this->client()->messages()->sendMime($this->domain, [$toEmail], $message->toString(), []);

        MailLog::info('Sent!');

        return array(
            'runtime_message_id' => StringHelper::cleanupMessageId($result->getId()),
            'status' => self::DELIVERY_STATUS_SENT,
        );
    }
}
