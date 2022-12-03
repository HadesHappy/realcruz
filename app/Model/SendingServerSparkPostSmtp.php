<?php

/**
 * SendingServerSparkPostSmtp class.
 *
 * Model class for SparkPost SMTP sending server
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

class SendingServerSparkPostSmtp extends SendingServerSparkPost
{
    protected $table = 'sending_servers';

    /**
     * Send the provided message.
     *
     * @return bool
     *
     * @param message
     */
    public function send($message, $params = [])
    {
        $transport = new \Swift_SmtpTransport($this->host, (int) $this->smtp_port, $this->smtp_protocol);
        $transport->setUsername($this->smtp_username);
        $transport->setPassword($this->smtp_password);

        // tracking bounce/feedback
        $msgId = $message->getHeaders()->get('X-Acelle-Message-Id')->getFieldBody();
        $message->getHeaders()->addTextHeader('X-MSYS-API', json_encode(['metadata' => ['runtime_message_id' => $msgId]]));

        // Create the Mailer using your created Transport
        $mailer = new \Swift_Mailer($transport);

        // Actually send
        $sent = $mailer->send($message);

        if ($sent) {
            MailLog::info('Sent!');

            return array(
                'status' => self::DELIVERY_STATUS_SENT,
            );
        } else {
            throw new \Exception('Unknown SMTP error');
        }
    }
}
