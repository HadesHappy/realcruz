<?php

/**
 * SendingServerSendGrid class.
 *
 * Abstract class for SendGrid sending servers
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
use SendGrid\Email;
use SendGrid\Content;
use SendGrid\Attachment;
use Acelle\Library\SendingServer\DomainVerificationInterface;

class SendingServerSendGrid extends SendingServer implements DomainVerificationInterface
{
    public const WEBHOOK = 'sendgrid';

    protected $table = 'sending_servers';
    public $client = null;
    public $isWebhookSetup = false;

    /**
     * Get authenticated to Mailgun and return the session object.
     *
     * @return mixed
     */
    public function client()
    {
        if (!$this->client) {
            if (is_null($this->subAccount)) {
                MailLog::info('Using master account');
                $this->client = new \SendGrid($this->api_key);
            } else {
                MailLog::info("Using subaccount {$this->subAccount->getSubAccountUsername()}");
                $this->client = new \SendGrid($this->subAccount->api_key);
            }
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

        MailLog::info('Setting up SendGrid webhooks');
        $subscribeUrl = StringHelper::joinUrl(Setting::get('url_delivery_handler'), self::WEBHOOK);
        $request_body = json_decode(
            '{
            "bounce": true,
            "click": false,
            "deferred": false,
            "delivered": false,
            "dropped": true,
            "enabled": true,
            "group_resubscribe": false,
            "group_unsubscribe": false,
            "open": false,
            "processed": false,
            "spam_report": true,
            "unsubscribe": false,
            "url": "'.$subscribeUrl.'"
            }'
        );
        $response = $this->client()->client->user()->webhooks()->event()->settings()->patch($request_body);

        if ($response->statusCode() == '200') {
            MailLog::info('Webhooks successfully set!');
        } else {
            throw new \Exception(sprintf('Cannot setup SendGrid webhook. Status code: %s. Body: %s', $response->statusCode(), $response->body()));
        }

        $this->isWebhookSetup = true;
    }

    /**
     * Get Message Id
     * Extract the message id from SendGrid response.
     *
     * @return string
     */
    public function getMessageId($headers)
    {
        preg_match('/(?<=X-Message-Id: ).*/', $headers, $matches);
        if (isset($matches[0])) {
            return $matches[0];
        } else {
            return;
        }
    }

    /**
     * Prepare the email object for sending.
     *
     * @return mixed
     */
    public function prepareEmail($message)
    {
        $fromEmail = array_keys($message->getFrom())[0];
        $fromName = (is_null($message->getFrom())) ? null : array_values($message->getFrom())[0];
        $toEmail = array_keys($message->getTo())[0];
        $toName = (is_null($message->getTo())) ? null : array_values($message->getTo())[0];
        $replyToEmail = (is_null($message->getReplyTo())) ? $fromEmail : array_keys($message->getReplyTo())[0];

        // Following RFC 1341, section 7.2
        //     If either text/html or text/plain are to be sent in your email
        //     text/plain needs to be first, followed by text/html, followed by any other content
        // So, use array_shift instead of array_pop
        // Also, sort the parts so that text/plain comes before text/html

        $parts = $message->getChildren();
        usort($parts, function ($a, $b) {
            if ($a->getContentType() == 'text/plain') {
                return -1;
            } elseif ($a->getContentType() == 'text/html') {
                return 0;
            } else {
                return 1;
            }
        });

        // skip attachment part
        $parts = array_map(function ($part) {
            if (method_exists($part, 'getDisposition')) { // only a part of type Swift_Mime_Attachment has this method
                // add later on
                return null;
            } else {
                return new Content($part->getContentType(), $part->getBody());
            }
        }, $parts);

        // remove null element
        $parts = array_filter($parts);

        $mail = new Mail(
            new Email($fromName, $fromEmail),
            $message->getSubject(),
            new Email($toName, $toEmail),
            array_shift($parts) // first content only
        );

        // set Reply-To header
        $mail->setReplyTo(['email' => $replyToEmail]);

        foreach ($parts as $part) {
            $mail->addContent($part);
        }

        foreach ($message->getChildren() as $part) {
            if (method_exists($part, 'getDisposition')) {
                $filename = basename($part->getFilename());
                $encoded = base64_encode($part->getBody());
                $attachment = new Attachment();
                $attachment->setType($part->getContentType());
                $attachment->setContent($encoded);
                $attachment->setDisposition("attachment");
                $attachment->setFilename($filename);
                $mail->addAttachment($attachment);
            }
        }

        $preserved = [
            'Content-Transfer-Encoding',
            'Content-Type',
            'MIME-Version',
            'Date',
            'Message-ID',
            'From',
            'Subject',
            'To',
            'Reply-To',
            'Subject',
            'From',
        ];

        foreach ($message->getHeaders()->getAll() as $header) {
            if (!in_array($header->getFieldName(), $preserved)) {
                $mail->addHeader($header->getFieldName(), $header->getFieldBody());
            }
        }

        // to track bounce/feedback notification
        $mail->addCustomArg('runtime_message_id', $message->getHeaders()->get('X-Acelle-Message-Id')->getFieldBody());

        return $mail;
    }

    /**
     * Get verified identities (domains and email addresses).
     *
     * @return bool
     */
    public function syncIdentities()
    {
        $response = $this->client()->client->whitelabel()->domains()->get();
        $json = json_decode($response->body(), true);
        if (array_key_exists('errors', $json)) {
            throw new \Exception('Failed to connect to SendGrid: '.$response->body());
        }

        $identities = [];
        foreach ($json as $domain) {
            $name = $domain['domain'];
            $identities[$name] = ['VerificationStatus' => $domain['valid'] == true];
        }

        // list of identities that are added by customers/users
        $addedByUsers = $this->sendingDomains()->whereIn('name', array_keys($identities))->get();

        foreach ($addedByUsers as $domain) {
            if (array_key_exists($domain->name, $identities)) {
                $identities[$domain->name]['UserId'] = $domain->customer->id;
                $identities[$domain->name]['UserName'] = $domain->customer->user->displayName();
            }
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
        $response = $this->client()->client->whitelabel()->domains()->get();
        $json = json_decode($response->body(), true);
        if (array_key_exists('errors', $json)) {
            throw new \Exception('Failed to connect to SendGrid: '.$response->body());
        }

        return true;
    }

    public function verifyDomain($domain): array
    {
        $body = json_decode('{
          "automatic_security": false,
          "custom_spf": false,
          "subdomain": "'.$this->getDefaultSubdomainName($domain).'",
          "default": false,
          "domain": "'.$domain.'"
        }');

        $response = $this->client()->client->whitelabel()->domains()->post($body);
        $result = json_decode($response->body(), true);

        if (array_key_exists('errors', $result)) {
            // in case of {"errors":[{"message":"An authenticated domain already exists for this URL. Please use a unique subdomain."}]}
            // => get the domain information

            $result = $this->getDomainVerificationInfo($domain);
            // if domain not found, then it is another error at first
            if (empty($result)) {
                throw new \Exception($response->body());
            }
        }

        $identityRecord = $result['dns']['mail_server'];
        $identityRecord['name'] = $identityRecord['host'];
        $identityRecord['value'] = $identityRecord['data'];
        $identityRecord['type'] = strtoupper($identityRecord['type']);

        $dkimRecord = $result['dns']['dkim'];
        $dkimRecord['name'] = $dkimRecord['host'];
        $dkimRecord['value'] = $dkimRecord['data'];
        $dkimRecord['type'] = strtoupper($dkimRecord['type']);

        $spfRecord = $result['dns']['subdomain_spf'];
        $spfRecord['name'] = $spfRecord['host'];
        $spfRecord['value'] = $spfRecord['data'];
        $spfRecord['type'] = strtoupper($spfRecord['type']);

        return [
            'identity' => $identityRecord,
            'dkim' => [ $dkimRecord ], // there may be more than one DKIM, AWS for example
            'spf' => [ $spfRecord ],
            'results' => [
                'identity' => false,
                'dkim' => false,
                'spf' => false,
            ],
        ];
    }

    public function checkDomainVerificationStatus($domain): array
    {
        $this->validateDomain($domain->name);
        $info = $this->getDomainVerificationInfo($domain->name);

        if (empty($info)) {
            throw new \Exception('Cannot verify domain against SendGrid');
        }

        $identity = $info['dns']['mail_server']['valid'];
        $dkim = $info['dns']['dkim']['valid'];
        $spf = $info['dns']['subdomain_spf']['valid'];

        $finalStatus = $identity && $dkim && $spf;

        return [ $identity, $dkim, $spf, $finalStatus ];
    }

    private function getDefaultSubdomainName($domain)
    {
        return substr(md5($domain), 0, 12);
    }

    private function getDomainVerificationInfo($domain)
    {
        $response = $this->client()->client->whitelabel()->domains()->get();
        $domains = json_decode($response->body(), true);

        $domains = array_values(array_filter($domains, function ($r) use ($domain) {
            return $domain == $r['domain'] && $this->getDefaultSubdomainName($domain) == $r['subdomain'];
        }));

        if (empty($domains)) {
            return null;
        } else {
            return $domains[0];
        }
    }

    public function validateDomain($domain)
    {
        // both dkim and identity
        $info = $this->getDomainVerificationInfo($domain);
        if (empty($info)) {
            return;
        }
        $result = $this->client()->client->whitelabel()->domains()->_($info['id'])->validate()->post();
        return $result;
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
        return true;
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

    public function setupBeforeSend($fromEmailAddress)
    {
        $this->setupWebhooks();
    }
}
