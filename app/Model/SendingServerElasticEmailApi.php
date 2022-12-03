<?php

/**
 * SendingServerElasticEmailApi class.
 *
 * Abstract class for Mailjet API sending server
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

class SendingServerElasticEmailApi extends SendingServerElasticEmail
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
        $this->enableCustomHeaders();

        $result = $this->sendElasticEmailV2Fixed($message);

        MailLog::info('Sent!');

        return array(
            'runtime_message_id' => $result,
            'status' => self::DELIVERY_STATUS_SENT,
        );
    }
}
