<?php

/**
 * AmazonSmtpTransport class.
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

class AmazonSmtpTransport extends \Swift_SmtpTransport
{
    /**
     * Array used to store raw SMTP responses.
     */
    private $rawResponses = array();

    /**
     * Overwrite the execute method.
     *
     * @return mixed
     */
    public function executeCommand($command, $codes = array(), &$failures = null, $pipeline = false, $address = null)
    {
        $response = parent::executeCommand($command, $codes, $failures, $pipeline, $address);
        $this->rawResponses[] = $response;

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
     * Get Amazon message ID from the last SMTP response.
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
     * Get an array of SMTP raw responses.
     *
     * @return array SMTP messages
     */
    public function getRawResponses()
    {
        return $this->rawResponses;
    }
}
