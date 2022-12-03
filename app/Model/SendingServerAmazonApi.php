<?php

/**
 * SendingServerAmazonApi class.
 *
 * Model class for Amazon API sending server
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

class SendingServerAmazonApi extends SendingServerAmazon
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
        $sent = $this->sesClient()->sendRawEmail(array(
            'RawMessage' => array(
                'Data' => $message->toString(),
            ),
        ));

        MailLog::info('Sent!');

        return array(
            'runtime_message_id' => $sent['MessageId'],
            'status' => self::DELIVERY_STATUS_SENT,
        );
    }
}
