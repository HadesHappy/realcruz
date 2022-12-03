<?php

/**
 * SendingServerAmazonSmtp class.
 *
 * Model class for Amazon SMTP sending server
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
use Acelle\Library\AmazonSmtpTransport;
use Exception;

class SendingServerAmazonSmtp extends SendingServerAmazon
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
        $transport = AmazonSmtpTransport::newInstance($this->host, (int) $this->smtp_port, $this->smtp_protocol);
        $transport->setUsername($this->smtp_username);
        $transport->setPassword($this->smtp_password);

        // Create the Mailer using your created Transport
        $mailer = new \Swift_Mailer($transport);

        // Actually send
        $sent = $mailer->send($message);

        if ($sent) {
            MailLog::info('Sent!');

            return array(
                'runtime_message_id' => $transport->getMessageId(),
                'status' => self::DELIVERY_STATUS_SENT,
            );
        } else {
            throw new Exception('Unknown SMTP error');
        }
    }
}
