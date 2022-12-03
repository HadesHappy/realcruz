<?php

/**
 * SendingServerMailgun class.
 *
 * Abstract class for Mailgun sending servers
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
use Mailgun\Mailgun;

class SendingServerMailgun extends SendingServer
{
    public const WEBHOOK = 'mailgun';

    protected $table = 'sending_servers';
    public $client = null;
    public $isWebhookSetup = false;

    // Inherit class to implementation of this method
    public function send($message, $params = array())
    {
        // for overwriting
    }

    /**
     * Get authenticated to Mailgun and return the session object.
     *
     * @return mixed
     */
    public function client()
    {
        if (!$this->client) {
            $this->client = Mailgun::create($this->api_key, $this->host);
        }

        return $this->client;
    }

    /**
     * Setup webhooks for processing bounce and feedback loop.
     *
     * @return mixed
     */
    public function setupWebhooks()
    {
        if ($this->isWebhookSetup) {
            return true;
        }

        MailLog::info('Setting up webhooks for bounce/complaints');

        $domain = $this->domain;
        $subscribeUrl = StringHelper::joinUrl(Setting::get('url_delivery_handler'), self::WEBHOOK);

        MailLog::info('Webhook set to: '.$subscribeUrl);

        try {
            $result = $this->client()->webhooks()->delete($domain, 'complained');
        } catch (\Exception $e) {
            // just ignore
        }

        try {
            $result = $this->client()->webhooks()->delete($domain, 'permanent_fail');
        } catch (\Exception $e) {
            // just ignore
        }

        $result = $this->client()->webhooks()->create($domain, 'complained', [ $subscribeUrl ]);
        $result = $this->client()->webhooks()->create($domain, 'permanent_fail', [ $subscribeUrl ]);

        MailLog::info('3 webhooks created');

        $this->isWebhookSetup = true;
    }

    /**
     * Get verified identities (domains and email addresses).
     *
     * @return bool
     */
    public function syncIdentities()
    {
        $domains = $this->client()->domains()->index()->getDomains();

        $identities = [];
        foreach ($domains as $domain) {
            $name = $domain->getName();
            $identities[$name] = ['VerificationStatus' => $domain->getState() == 'active'];
        }

        $identityStore = $this->getIdentityStore();
        $identityStore->update($identities);

        $options = $this->getOptions();
        $options['identities'] = $identityStore->get();
        $this->setOptions($options);
        $this->save();
    }

    /**
     * Check the sending server settings, make sure it does work.
     *
     * @return bool
     */
    public function test()
    {
        $response = $this->client()->domains()->index();

        return true;
    }

    /**
     * Allow user to verify his/her own sending domain against Acelle Mail.
     *
     * @return bool
     */
    public function allowVerifyingOwnDomains()
    {
        return false;
    }

    /**
     * Allow user to verify his/her own sending domain against Acelle Mail.
     *
     * @return bool
     */
    public function allowVerifyingOwnEmails()
    {
        return false;
    }

    /**
     * Allow user to verify his/her own emails against AWS.
     *
     * @return bool
     */
    public function allowVerifyingOwnDomainsRemotely()
    {
        return false;
    }

    /**
     * Allow user to verify his/her own emails against AWS.
     *
     * @return bool
     */
    public function allowVerifyingOwnEmailsRemotely()
    {
        return false;
    }

    public static function handleNofification()
    {
        MailLog::configure(storage_path().'/logs/handler-mailgun.log');

        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, true);

        if (isset($input['signature'])) {
            if ($input['event-data']['event'] == 'complained') {
                $feedbackLog = new FeedbackLog();
                $feedbackLog->runtime_message_id = StringHelper::cleanupMessageId($input['event-data']['message']['headers']['message-id']);
                // For Mailgun, runtime_message_id EQUIV. message_id
                $feedbackLog->message_id = $feedbackLog->runtime_message_id;
                $feedbackLog->feedback_type = 'spam';
                $feedbackLog->raw_feedback_content = $inputJSON;
                $feedbackLog->save();
                MailLog::info('Feedback recorded for message '.$feedbackLog->runtime_message_id);
                $subscriber = $feedbackLog->findSubscriberByRuntimeMessageId();
                if (!is_null($subscriber)) {
                    $subscriber->sendToBlacklist($feedbackLog->raw_feedback_content);
                }
            } elseif ($input['event-data']['event'] == 'failed') {
                $bounceLog = new BounceLog();
                $bounceLog->runtime_message_id = StringHelper::cleanupMessageId($input['event-data']['message']['headers']['message-id']);
                // For Mailgun, runtime_message_id EQUIV. message_id
                $bounceLog->message_id = $bounceLog->runtime_message_id;
                $bounceLog->bounce_type = BounceLog::HARD;
                $bounceLog->raw = $inputJSON;
                $bounceLog->save();
                MailLog::info('Bounce recorded for message '.$bounceLog->runtime_message_id);
                MailLog::info('Adding email to blacklist');
                $subscriber = $bounceLog->findSubscriberByRuntimeMessageId();
                if (!is_null($subscriber)) {
                    $subscriber->sendToBlacklist($bounceLog->raw);
                }
            }
        } else {
            MailLog::warning('Invalid request: '.$inputJSON);
        }
        header('X-PHP-Response-Code: 200', true, 200);
    }

    public function setupBeforeSend($fromEmailAddress)
    {
        $this->setupWebhooks();
    }
}
