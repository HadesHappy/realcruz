<?php

/**
 * ExtendedSmtpTransport class.
 *
 * This is the extended edition of the Swift_SmtpTransport
 * Extended feature supports a new method that helps record SMTP raw response
 *
 * LICENSE: This product includes software developed at
 * the Acelle Co., Ltd. (http://acellemail.com/).
 *
 * @category   Extension
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

namespace Acelle\Library;

class ExtendedSmtpTransport extends \Swift_SmtpTransport
{
    /**
     * Array used to store raw SMTP responses.
     */
    private $rawResponses = array();
    private $rawTrasmissionData = array();

    /**
     * Overwrite the execute method.
     *
     * @return mixed
     */
    public function executeCommand($command, $codes = array(), &$failures = null, $pipeline = false, $address = null)
    {
        $this->rawTrasmissionData[] = $command;
        $response = parent::executeCommand($command, $codes, $failures, $pipeline, $address);
        $this->rawResponses[] = $response;
        $this->rawTrasmissionData[] = $response;

        return $response;
    }

    /**
     * Overwrite the initialization method.
     *
     * @return mixed
     */
    public static function newInstance($host = 'localhost', $port = 25, $security = null)
    {
        return new self($host, $port, $security);
    }

    /**
     * Get Elastic message ID from the last SMTP response.
     * which looks like this: "250 OK 5c4764a2-93a7-4914-91e2-b6c6f57aff9d".
     *
     * @return string messageId
     */
    public function getMessageId()
    {
        $messageId = null;
        foreach ($this->rawResponses as $e) {
            preg_match('/(?<=250 ok\s)[^\s]*/i', $e, $matched);
            if (sizeof($matched) > 0) {
                $messageId = $matched[0];
            }
        }

        return $messageId;
    }

    /**
     * Get SMTP raw responses (for debugging).
     *
     * @return array SMTP responses
     */
    public function getRawTrasmissionData()
    {
        return $this->rawTrasmissionData();
    }
}
