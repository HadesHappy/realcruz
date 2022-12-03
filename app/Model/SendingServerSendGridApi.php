<?php

/**
 * SendingServerSendGridApi class.
 *
 * Abstract class for SendGrid API sending server
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
use SendGrid\Mail;

class SendingServerSendGridApi extends SendingServerSendGrid
{
    protected $table = 'sending_servers';

    /**
     * Send the provided message.
     *
     * @return bool
     *
     * @param message
     */
    // Inherit class to implementation of this method
    public function send($message, $params = array())
    {
        $msgId = $message->getHeaders()->get('X-Acelle-Message-Id')->getFieldBody();

        $mail = $this->prepareEmail($message);
        $response = $this->client()->client->mail()->send()->post($mail);
        $statusCode = $response->statusCode();

        # if response from SendGrid is 200, 202, 2xx
        if (preg_match('/^2../i', $statusCode)) {
            MailLog::info('Sent!');

            $result = array(
                // @deprecated
                // 'runtime_message_id' => StringHelper::cleanupMessageId($this->getMessageId($response->headers())),
                'runtime_message_id' => $msgId,
                'status' => self::DELIVERY_STATUS_SENT,
            );

            if (!is_null($this->subAccount)) {
                $result['sub_account_id'] = $this->subAccount->id;
            }

            return $result;
        } else {
            throw new \Exception("{$statusCode} ".$response->body());
        }
    }
}
