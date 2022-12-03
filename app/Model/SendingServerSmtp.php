<?php

/**
 * SendingServerSmtp class.
 *
 * Abstract class for standard SMTP sending server
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

class SendingServerSmtp extends SendingServer
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
        $transport = new \Swift_SmtpTransport($this->host, (int) $this->smtp_port, $this->smtp_protocol);
        $transport->setUsername($this->smtp_username);
        $transport->setPassword($this->smtp_password);
        // in case of: stream_socket_enable_crypto(): SSL operation failed with code 1. OpenSSL Error messages: error:14090086:SSL routines:SSL3_GET_SERVER_CERTIFICATE:certificate verify failed
        $transport->setStreamOptions(array('ssl' => array('allow_self_signed' => true, 'verify_peer' => false, 'verify_peer_name' => false)));

        // setup bounce handler: specify the Return-Path
        // if ($this->bounceHandler) {
        //     $message->setReturnPath($this->bounceHandler->username);
        // }

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

    /**
     * Check the sending server settings, make sure it does work.
     *
     * @return bool
     */
    public function test()
    {
        $transport = new \Swift_SmtpTransport($this->host, (int) $this->smtp_port, $this->smtp_protocol);
        $transport->setUsername($this->smtp_username);
        $transport->setPassword($this->smtp_password);

        // in case of: stream_socket_enable_crypto(): SSL operation failed with code 1. OpenSSL Error messages: error:14090086:SSL routines:SSL3_GET_SERVER_CERTIFICATE:certificate verify failed
        $transport->setStreamOptions(array('ssl' => array('allow_self_signed' => true, 'verify_peer' => false, 'verify_peer_name' => false)));

        // Create the Mailer using your created Transport
        $mailer = new \Swift_Mailer($transport);
        $mailer->getTransport()->start();

        return true;
    }

    public function allowVerifyingOwnEmailsRemotely()
    {
        return false;
    }

    public function allowVerifyingOwnDomainsRemotely()
    {
        return false;
    }

    public function syncIdentities()
    {
        // just do nothing
    }

    public static function instantiateFromSettings($settings = [])
    {
        $properties = [ 'host', 'smtp_port',  'smtp_protocol', 'smtp_username', 'smtp_password', 'from_name', 'from_address' ];
        $required = ['host', 'smtp_port', 'smtp_username', 'smtp_password', 'from_address'];

        $server = new self();

        // validate
        foreach ($properties as $property) {
            if ((!array_key_exists($property, $settings) || empty($settings[$property])) && in_array($property, $required)) {
                throw new \Exception("Cannot instantiate SMTP mailer, '{$property}' property is missing");
            }

            $server->{$property} = $settings[$property];
        }

        return $server;
    }

    public function setupBeforeSend($fromEmailAddress)
    {
        //
    }

    public function allowOtherSendingDomains()
    {
        return true;
    }
}
