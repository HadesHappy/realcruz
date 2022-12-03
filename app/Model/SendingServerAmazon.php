<?php

/**
 * SendingServerAmazon class.
 *
 * An abstract class for different types of Amazon sending servers
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
use Acelle\Library\Lockable;
use Acelle\Library\IdentityStore;
use Acelle\Library\SendingServer\DomainVerificationInterface;
use Acelle\Model\SendingDomain;
use Aws\Route53\Route53Client;
use Acelle\Library\Facades\Hook;
use Exception;
use function Acelle\Helpers\isValidPublicHostnameOrIpAddress;

class SendingServerAmazon extends SendingServer implements DomainVerificationInterface
{
    public const SNS_TOPIC = 'ACELLEHANDLER';
    public const SNS_TYPE = 'amazon'; // @TODO
    public const SPF_DNS_RECORD = 'v=spf1 include:amazonses.com ~all';

    public $notificationTypes = array('Bounce', 'Complaint');
    public $snsClient = null;
    public $sesClient = null;
    public $isSnsSetup = false;

    /**
     * Initiate a AWS SNS session and return the session object (snsClient).
     *
     * @return mixed
     */
    public function snsClient()
    {
        if (!$this->snsClient) {
            $this->snsClient = \Aws\Sns\SnsClient::factory(array(
                'credentials' => array(
                    'key' => trim($this->aws_access_key_id),
                    'secret' => trim($this->aws_secret_access_key),
                ),
                'region' => $this->aws_region,
                'version' => '2010-03-31',
            ));
        }

        return $this->snsClient;
    }

    /**
     * Initiate a AWS SES session and return the session object (snsClient).
     *
     * @return mixed
     */
    public function sesClient()
    {
        if (!$this->sesClient) {
            $this->sesClient = \Aws\Ses\SesClient::factory(array(
                'credentials' => array(
                    'key' => trim($this->aws_access_key_id),
                    'secret' => trim($this->aws_secret_access_key),
                ),
                'region' => $this->aws_region,
                'version' => '2010-12-01',
            ));
        }

        return $this->sesClient;
    }

    public function verifyDomain($domain): array
    {
        $identity = $this->verifyDomainIdentity($domain);
        $dkim = $this->verifyDomainDkim($domain);

        return [
            'identity' => $identity,
            'dkim' =>  $dkim,
            'spf' => [
                [
                    'type' => 'TXT',
                    'name' => $domain,
                    'value' => self::SPF_DNS_RECORD
                ]
            ],
            'results' => [
                'identity' => false,
                'dkim' => false,
                'spf' => false,
            ],
        ];
    }

    public function verifyDomainIdentity($domain)
    {
        $result = $this->sesClient()->verifyDomainIdentity([
            'Domain' => $domain,
        ]);

        $token = $result->toArray()['VerificationToken'];
        return [
            'type' => 'TXT',
            'name' => "_amazonses.{$domain}",
            'value' => $token,
        ];
    }

    public function verifyDomainDkim($domain)
    {
        $result = $this->sesClient()->verifyDomainDkim([
            'Domain' => $domain,
        ]);

        $tokens = $result->toArray()['DkimTokens'];

        Hook::execute('after_verify_dkim_against_aws_ses', [$domain, $tokens]);

        return array_map(function ($token) use ($domain) {
            return [
                'type' => 'CNAME',
                'name' => "{$token}._domainkey.{$domain}",
                'value' => "{$token}.dkim.amazonses.com",
            ];
        }, $tokens);
    }

    /**
     * Setup AWS SNS for bounce and feedback loop.
     *
     * @return mixed
     */
    public function setupSns($fromEmailAddress)
    {
        if ($this->isSnsSetup) {
            return true;
        }

        $handlingHost = parse_url(Setting::get('url_delivery_handler'), PHP_URL_HOST);

        if (!isValidPublicHostnameOrIpAddress($handlingHost)) {
            throw new Exception(sprintf('You are sending through AWS. However, the current hostname (%s) does not seem to be a valid public URL. As a result, bounce or feedback handling will not work', $handlingHost));
        }

        MailLog::info('Set up Amazon SNS for email delivery tracking');

        $awsIdentity = $fromEmailAddress;
        $verifyByDomain = false;
        try {
            $this->sesClient()->setIdentityFeedbackForwardingEnabled(array(
                'Identity' => $awsIdentity,
                'ForwardingEnabled' => true,
            ));
        } catch (\Exception $e) {
            $verifyByDomain = true;
            MailLog::warning("From Email address {$fromEmailAddress} not verified by Amazon SES, using domain instead");
        }

        if ($verifyByDomain) {
            // Use domain name as Aws Identity
            $awsIdentity = substr(strrchr($fromEmailAddress, '@'), 1); // extract domain from email
            $this->sesClient()->setIdentityFeedbackForwardingEnabled(array(
                'Identity' => $awsIdentity, // extract domain from email
                'ForwardingEnabled' => true,
            ));
        }

        $topicResponse = $this->snsClient()->createTopic(array('Name' => self::SNS_TOPIC));
        $subscribeUrl = StringHelper::joinUrl(Setting::get('url_delivery_handler'), self::SNS_TYPE);

        $subscribeResponse = $this->snsClient()->subscribe(array(
            'TopicArn' => $topicResponse->get('TopicArn'),
            'Protocol' => stripos($subscribeUrl, 'https') === 0 ? 'https' : 'http',
            'Endpoint' => $subscribeUrl,
        ));

        if (stripos($subscribeResponse->get('SubscriptionArn'), 'pending') === false) {
            $this->subscription_arn = $result->get('SubscriptionArn');
        }

        foreach ($this->notificationTypes as $type) {
            $this->sesClient()->setIdentityNotificationTopic(array(
                'Identity' => $awsIdentity,
                'NotificationType' => $type,
                'SnsTopic' => $topicResponse->get('TopicArn'),
            ));
        }

        $this->isSnsSetup = true;
    }

    /**
     * Setup SNS, make sure the request limit (in case of multi-process) is less than 1 request / second.
     *
     * @return mixed
     */
    public function setupSnsThreadSafe($fromEmailAddress)
    {
        if ($this->isSnsSetup) {
            return true;
        }

        $lock = new Lockable(storage_path('locks/sending-server-sns-'.$this->uid));
        $lock->getExclusiveLock(function () use ($fromEmailAddress) {
            $this->setupSns($fromEmailAddress);
            sleep(1); // SNS request rate limit
        });
    }

    /**
     * Get verified identities (domains and email addresses).
     *
     * @return bool
     */
    public function syncIdentities()
    {
        // Merge the list of identities from Amazon to the local sending domains to get customer information
        $emailOrDomains = $this->sesClient()->listIdentities([
            'MaxItems' => 1000, # @todo, need pagination here
        ])->toArray()['Identities'];

        $identities = [];

        // AWS: can only get verification attributes for up to 100 identities at a time
        foreach (array_chunk($emailOrDomains, 100) as $chunk100) {
            $identities100 = $this->sesClient()->getIdentityVerificationAttributes([
                'Identities' => $chunk100,
            ])->toArray()['VerificationAttributes'];

            $identities = array_merge($identities, $identities100);
        }

        // Domains added by users
        $domainsByUsers = $this->sendingDomains()->whereIn('name', $emailOrDomains)->get();
        $sendersByUsers = $this->senders()->whereIn('email', $emailOrDomains)->get();

        foreach ($domainsByUsers as $domain) {
            if (array_key_exists($domain->name, $identities)) {
                $identities[$domain->name]['UserId'] = $domain->customer->id;
                $identities[$domain->name]['UserName'] = $domain->customer->user->displayName();
            }
        }

        foreach ($sendersByUsers as $sender) {
            if (array_key_exists($sender->email, $identities)) {
                $identities[$sender->email]['UserId'] = $sender->customer->id;
                $identities[$sender->email]['UserName'] = $sender->customer->user->displayName();
            }
        }

        // New identities fetched from the server, UserId and UserName keys are not available yet, just set it to Null
        foreach ($identities as $key => $attributes) {
            if (!array_key_exists('UserId', $attributes)) {
                $identities[$key]['UserId'] = null;
            }

            if (!array_key_exists('UserName', $attributes)) {
                $identities[$key]['UserName'] = null;
            }
        }

        $identityStore = $this->getIdentityStore();
        $identityStore->update($identities);

        $options = $this->getOptions();
        $options['identities'] = $identityStore->get();
        $this->setOptions($options);
        $this->save();
    }

    public function checkDomainVerificationStatus($domain): array
    {
        // Identity
        $status = $identitiesWithAttributes = $this->sesClient()->getIdentityVerificationAttributes([
            'Identities' => [$domain->name],
        ])->toArray()['VerificationAttributes'][$domain->name]['VerificationStatus'];

        $identity = 'Success' == $status;

        // DKIM
        $status = $identitiesWithAttributes = $this->sesClient()->getIdentityDkimAttributes([
            'Identities' => [$domain->name],
        ])->toArray()['DkimAttributes'][$domain->name]['DkimVerificationStatus'];

        $dkim = 'Success' == $status;

        // HACK: if DKIM is "verified" then Identity is automatically "verified" too
        if ($dkim) {
            $identity = $dkim;
        }

        // SPF
        $spf = $domain->verifySpf($this->getSpfHost());
        $finalStatus = $identity && $dkim; // SPF is optional

        // Return all
        return [$identity, $dkim, $spf, $finalStatus];
    }

    /**
     * Check an email address if it is verified against AWS.
     *
     * @return bool
     */
    public function sendVerificationEmail($identity)
    {
        // send custom template to Amazon
        $templateName = $this->createCustomVerificationEmailTemplateFor($identity);

        $this->sesClient()->SendCustomVerificationEmail([
            'EmailAddress' => $identity->email,
            'TemplateName' => $templateName,
            'SuccessRedirectionURL' => $identity->generateVerificationResultUrl(),
            'FailureRedirectionURL' => $identity->generateVerificationResultUrl(),
        ]);
    }

    /**
     * Check if AWS actions are allowed.
     *
     * @return bool
     */
    public static function testConnection($key, $secret, $region)
    {
        $iamClient = \Aws\Iam\IamClient::factory(array(
            'credentials' => array(
                'key' => trim($key),
                'secret' => trim($secret),
            ),
            'region' => $region,
            'version' => '2010-05-08',
        ));

        // getting API caller
        $arn = $iamClient->getUser()->get('User')['Arn'];

        $username = array_values(array_slice(explode(':', $arn), -1))[0];
        if ($username == 'root') {
            return true;
        }

        $actions = ['ses:VerifyEmailIdentity', 'ses:GetIdentityVerificationAttributes', 'ses:ListIdentities', 'ses:SetIdentityFeedbackForwardingEnabled', 'sns:CreateTopic', 'sns:Subscribe', 'sns:SetIdentityNotificationTopic'];
        $results = $iamClient->simulatePrincipalPolicy(['PolicySourceArn' => $arn, 'ActionNames' => $actions])->toArray();
        foreach ($results['EvaluationResults'] as $result) {
            $action = $result['EvalActionName'];
            $decision = $result['EvalDecision'];

            if ($decision != 'allowed') {
                throw new \Exception("Action {$action} is not allowed");
            }
        }

        return true;
    }

    /**
     * Check if AWS actions are allowed for the corresponding instance.
     *
     * @return bool
     */
    public function test()
    {
        return self::testConnection(
            $this->aws_access_key_id,
            $this->aws_secret_access_key,
            $this->aws_region
        );
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

    public function createCustomVerificationEmailTemplateFor($identity)
    {
        if (empty($this->default_from_email)) {
            throw new \Exception("Sending server `{$this->name}` does not have a 'Default FROM Email Address'. Please go to admin area, then sending server setting dashboard and update this value for the sending server");
        }

        // Take an available name slot
        $name = $this->generateCustomVerificationEmailTemplateName();

        // Get the template
        $template = Layout::where('alias', 'sender_verification_email_for_amazon_ses')->first();

        // Replace tags
        $html = $template->content;
        $html = str_replace('{USER_NAME}', $identity->name, $html);
        $html = str_replace('{USER_EMAIL}', $identity->email, $html);
        $redirectUrl = $identity->generateVerificationResultUrl();

        // Push to Amazon
        $result = $this->sesClient()->CreateCustomVerificationEmailTemplate([
            'TemplateName' => $name,
            'TemplateSubject' => $template->subject,
            'TemplateContent' => $html,
            'FromEmailAddress' => $this->default_from_email,
            'FailureRedirectionURL' => $redirectUrl,
            'SuccessRedirectionURL' => $redirectUrl,
        ]);

        return $name;
    }

    public function deleteCustomVerificationEmailTemplateIfExists($name)
    {
        try {
            // Check if template already exists for $identity
            $this->sesClient()->DeleteCustomVerificationEmailTemplate([
                'TemplateName' => $name,
            ]);
        } catch (Exception $ex) {
            // Just fine
        }
    }

    private function generateCustomVerificationEmailTemplateName()
    {
        $pattern = "acelle-";
        $from = 1;
        $max = 50;

        // There are 50 slots for a limit of 50 templates
        $r = $this->sesClient()->ListCustomVerificationEmailTemplates(['MaxResults' => $max]);

        // Something like ['acelle-1', 'acelle-2', 'acelle-5', ..., 'acelle-50'];
        $names = array_map(function ($record) {
            return $record['TemplateName'];
        }, $r->toArray()['CustomVerificationEmailTemplates']);

        // Delete template if it is not "acelle-"
        foreach ($names as $key => $name) {
            if (strpos($name, $pattern) !== 0) {
                unset($names[$key]);
                $this->deleteCustomVerificationEmailTemplateIfExists($name);
            }
        }

        // Something like [1, 2, 5, ..., 50];
        $numbers = array_map(function ($name) {
            return (int)explode('-', $name)[1];
        }, $names);

        // Take $selected slot and make $next slot available
        list($selected, $next) = $this->pickAvailableSlot($from, $max, $numbers);

        // Take selected template (delete if exists)
        $selectedTemplate = $pattern.$selected;
        $this->deleteCustomVerificationEmailTemplateIfExists($selectedTemplate);

        // Delete next slot if exists
        if (!is_null($next)) {
            $nextTemplate = $pattern.$next;
            $this->deleteCustomVerificationEmailTemplateIfExists($nextTemplate);
        }

        return $selectedTemplate;
    }

    public function pickAvailableSlot($from, $max, $list)
    {
        // Check for any available slot, from 1..50
        $selected = null;
        for ($i = $from; $i <= $max; $i += 1) {
            if (!in_array($i, $list)) {
                // Slot available, take it
                $selected = $i;
                break;
            }
        }

        // In case all slots are already filled
        // Then get back to the first slot
        $selected = $selected ?: $from;

        if ($selected < $max) {
            $next = $selected + 1;
        } else {
            $next = null;
        }

        return [$selected, $next];
    }

    public function getSpfHost()
    {
        return 'amazonses.com';
    }

    public function setupBeforeSend($fromEmailAddress)
    {
        $this->setupSnsThreadSafe($fromEmailAddress);
    }
}
