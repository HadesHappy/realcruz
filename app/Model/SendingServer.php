<?php

/**
 * SendingServer class.
 *
 * An abstract class for different types of sending servers
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

use Illuminate\Database\Eloquent\Model;
use Acelle\Library\Log as MailLog;
use Acelle\Library\IdentityStore;
use Acelle\Library\ExtendedSwiftMessage;
use Carbon\Carbon;
use Acelle\Library\StringHelper;
use Acelle\Library\Notification\BackendError as BackendErrorNotification;
use Acelle\Library\Facades\Hook;
use Acelle\Library\Traits\HasUid;
use Acelle\Library\Traits\HasQuota;
use Acelle\Library\Contracts\HasQuota as HasQuotaInterface;
use Acelle\Library\QuotaManager;
use Exception;

class SendingServer extends Model implements HasQuotaInterface
{
    use HasUid;
    use HasQuota;

    public const DELIVERY_STATUS_SENT = 'sent';
    public const DELIVERY_STATUS_FAILED = 'failed';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    // TYPE
    public const TYPE_AMAZON_API = 'amazon-api';
    public const TYPE_AMAZON_SMTP = 'amazon-smtp';
    public const TYPE_SENDGRID_API = 'sendgrid-api';
    public const TYPE_SENDGRID_SMTP = 'sendgrid-smtp';
    public const TYPE_MAILGUN_API = 'mailgun-api';
    public const TYPE_MAILGUN_SMTP = 'mailgun-smtp';
    public const TYPE_ELASTICEMAIL_API = 'elasticemail-api';
    public const TYPE_ELASTICEMAIL_SMTP = 'elasticemail-smtp';
    public const TYPE_SPARKPOST_API = 'sparkpost-api';
    public const TYPE_SPARKPOST_SMTP = 'sparkpost-smtp';
    public const TYPE_SENDMAIL = 'sendmail';
    public const TYPE_SMTP = 'smtp';

    protected $quotaTracker;
    protected $subAccount;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     * @note important! consider updating the $fillable variable, it will affect some other methods
     */
    protected $fillable = [
        'name', 'type', 'host', 'aws_access_key_id', 'aws_secret_access_key', 'aws_region', 'domain', 'api_key', 'api_secret_key', 'smtp_username',
        'smtp_password', 'smtp_port', 'smtp_protocol', 'quota_value', 'sendmail_path', 'quota_base', 'quota_unit',
        'bounce_handler_id', 'feedback_loop_handler_id', 'status', 'default_from_email',
    ];

    // Supported server types
    public static $serverMapping = array(
        self::TYPE_AMAZON_API => 'SendingServerAmazonApi',
        self::TYPE_AMAZON_SMTP => 'SendingServerAmazonSmtp',
        self::TYPE_SMTP => 'SendingServerSmtp',
        self::TYPE_SENDMAIL => 'SendingServerSendmail',
        self::TYPE_MAILGUN_API => 'SendingServerMailgunApi',
        self::TYPE_MAILGUN_SMTP => 'SendingServerMailgunSmtp',
        self::TYPE_SENDGRID_API => 'SendingServerSendGridApi',
        self::TYPE_SENDGRID_SMTP => 'SendingServerSendGridSmtp',
        self::TYPE_ELASTICEMAIL_API => 'SendingServerElasticEmailApi',
        self::TYPE_ELASTICEMAIL_SMTP => 'SendingServerElasticEmailSmtp',
        self::TYPE_SPARKPOST_API => 'SendingServerSparkPostApi',
        self::TYPE_SPARKPOST_SMTP => 'SendingServerSparkPostSmtp',
    );

    /**
     * Tracking logs.
     *
     * @return collection
     */
    public function trackingLogs()
    {
        return $this->hasMany('Acelle\Model\TrackingLog', 'sending_server_id')->orderBy('created_at', 'asc');
    }

    /**
     * Plans.
     *
     * @return collection
     */
    public function plans()
    {
        return $this->belongsToMany('Acelle\Model\Plan', 'plans_sending_servers');
    }

    /**
     * Plans.
     *
     * @return collection
     */
    public function plansSendingServers()
    {
        return $this->hasMany('Acelle\Model\PlansSendingServer', 'sending_server_id');
    }

    /**
     * Get the bounce handler.
     */
    public function bounceHandler()
    {
        return $this->belongsTo('Acelle\Model\BounceHandler');
    }

    public function sendingDomains()
    {
        return $this->hasMany('Acelle\Model\SendingDomain', 'sending_server_id');
    }

    /**
     * Senders.
     *
     * @return collection
     */
    public function senders()
    {
        return $this->hasMany('Acelle\Model\Sender', 'sending_server_id');
    }

    /**
     * Map a server to its class type and retrive an instance from the database.
     *
     * @return mixed
     *
     * @param campaign
     */
    public static function mapServerType($server)
    {
        if (array_key_exists($server->type, self::$serverMapping)) {
            // Old sending server types
            $class_name = '\Acelle\Model\\'.self::$serverMapping[$server->type];
        } else {
            $extendedTypes = Hook::execute('register_sending_server');
            foreach ($extendedTypes as $meta) {
                if ($meta['type'] == $server->type) {
                    $class_name = $meta['class'];
                }
            }
        }

        if (!isset($class_name)) {
            throw new Exception('Unknown sending server type '.$server->type);
        }

        if ($server->id) {
            $instance = $class_name::find($server->id);
        } else {
            $instance = new $class_name(['type' => $server->type]);
        }

        $instance->fill($server->getAttributes());

        return $instance;
    }

    /**
     * Get all items.
     *
     * @return collect
     */
    public function getVerp($recipient)
    {
        if (is_object($this->bounceHandler)) {
            $validator = \Validator::make(
                ['email' => $this->bounceHandler->username],
                ['email' => 'required|email']
            );

            if ($validator->passes()) {
                // @todo disable VERP as it is not supported by all mailbox
                // return str_replace('@', '+'.str_replace('@', '=', $recipient).'@', $this->bounceHandler->username);
                return $this->bounceHandler->username;
            } else {
                // @todo raise an error here, hold off the entire campaign
                return $this->bounceHandler->email;
            }
        }

        return;
    }

    /**
     * Get all items.
     *
     * @return collect
     */
    public static function getAll()
    {
        return self::where('status', '=', 'active');
    }

    /**
     * Associations.
     *
     * @var object | collect
     */
    public function customer()
    {
        return $this->belongsTo('Acelle\Model\Customer');
    }

    public function admin()
    {
        return $this->belongsTo('Acelle\Model\Admin');
    }

    /**
     * Filter items.
     *
     * @return collect
     */
    public static function scopeFilter($query, $request)
    {
        $query = $query->select('sending_servers.*');

        // filters
        $filters = $request->all();
        if (!empty($filters)) {
            if (!empty($filters['type'])) {
                $query = $query->where('sending_servers.type', '=', $filters['type']);
            }
        }

        // Other filter
        if (!empty($request->customer_id)) {
            $query = $query->where('sending_servers.customer_id', '=', $request->customer_id);
        }

        if (!empty($request->admin_id)) {
            $query = $query->where('sending_servers.admin_id', '=', $request->admin_id);
        }
    }

    /**
     * Search items.
     *
     * @return collect
     */
    public static function scopeSearch($query, $keyword)
    {
        // Keyword
        if (!empty(trim($keyword))) {
            foreach (explode(' ', trim($keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('sending_servers.name', 'like', '%'.$keyword.'%')
                        ->orWhere('sending_servers.type', 'like', '%'.$keyword.'%')
                        ->orWhere('sending_servers.host', 'like', '%'.$keyword.'%');
                });
            }
        }
    }

    /**
     * Items per page.
     *
     * @var array
     */
    public static $itemsPerPage = 25;

    /**
     * Type of server.
     *
     * @return object
     */
    public static function types()
    {
        return [
            self::TYPE_AMAZON_SMTP => [
                'cols' => [
                    'host' => 'required',
                    'aws_access_key_id' => 'required',
                    'aws_secret_access_key' => 'required',
                    'aws_region' => 'required',
                    'smtp_username' => 'required',
                    'smtp_password' => 'required',
                    'smtp_port' => 'required',
                    'smtp_protocol' => 'required',
                ],
                'settings' => [
                    'name' => 'required',
                    'default_from_email' => 'email',
                ],
            ],
            self::TYPE_AMAZON_API => [
                'cols' => [
                    'aws_access_key_id' => 'required',
                    'aws_secret_access_key' => 'required',
                    'aws_region' => 'required',
                ],
                'settings' => [
                    'name' => 'required',
                    'default_from_email' => 'email',
                ],
            ],
            self::TYPE_SENDGRID_SMTP => [
                'cols' => [
                    'api_key' => 'required',
                    'host' => 'required',
                    'smtp_username' => 'required',
                    'smtp_password' => 'required',
                    'smtp_port' => 'required',
                ],
                'settings' => [
                    'name' => 'required',
                    'default_from_email' => 'email',
                ],
            ],
            self::TYPE_SENDGRID_API => [
                'cols' => [
                    'api_key' => 'required',
                ],
                'settings' => [
                    'name' => 'required',
                    'default_from_email' => 'email',
                ],
            ],
            self::TYPE_MAILGUN_API => [
                'cols' => [
                    'api_key' => 'required',
                    'domain' => 'required',
                    'host' => 'required',
                ],
                'settings' => [
                    'name' => 'required',
                    'default_from_email' => 'email',
                ],
            ],
            self::TYPE_MAILGUN_SMTP => [
                'cols' => [
                    'domain' => 'required',
                    'api_key' => 'required',
                    'host' => 'required',
                    'aws_region' => 'required',
                    'smtp_username' => 'required',
                    'smtp_password' => 'required',
                    'smtp_port' => 'required',
                    'smtp_protocol' => 'required',
                ],
                'settings' => [
                    'name' => 'required',
                    'default_from_email' => 'email',
                ],
            ],
            self::TYPE_ELASTICEMAIL_API => [
                'cols' => [
                    'api_key' => 'required',
                ],
                'settings' => [
                    'name' => 'required',
                    'default_from_email' => 'email',
                ],
            ],
            self::TYPE_ELASTICEMAIL_SMTP => [
                'cols' => [
                    'api_key' => 'required',
                    'host' => 'required',
                    'smtp_username' => 'required',
                    'smtp_password' => 'required',
                    'smtp_port' => 'required',
                ],
                'settings' => [
                    'name' => 'required',
                    'default_from_email' => 'email',
                ],
            ],
            self::TYPE_SPARKPOST_API => [
                'cols' => [
                    'host' => 'required',
                    'api_key' => 'required',
                ],
                'settings' => [
                    'name' => 'required',
                    'default_from_email' => 'email',
                ],
            ],
            self::TYPE_SPARKPOST_SMTP => [
                'cols' => [
                    'api_key' => 'required',
                    'host' => 'required',
                    'smtp_username' => 'required',
                    'smtp_password' => 'required',
                    'smtp_port' => 'required',
                    'smtp_protocol' => '',
                ],
                'settings' => [
                    'name' => 'required',
                    'default_from_email' => 'email',
                ],
            ],
            self::TYPE_SMTP => [
                'cols' => [
                    'host' => 'required',
                    'smtp_username' => 'required',
                    'smtp_password' => 'required',
                    'smtp_port' => 'required',
                    'smtp_protocol' => '',
                ],
                'settings' => [
                    'name' => 'required',
                    'default_from_email' => 'email',
                    'bounce_handler_id' => '',
                    'feedback_loop_handler_id' => '',
                ],
            ],
            self::TYPE_SENDMAIL => [
                'cols' => [
                    'sendmail_path' => 'required',
                ],
                'settings' => [
                    'name' => 'required',
                    'default_from_email' => 'email',
                    'bounce_handler_id' => '',
                    'feedback_loop_handler_id' => '',
                ],
            ],
        ];
    }

    /**
     * Type of server.
     *
     * @return object
     */
    public static function frontendTypes()
    {
        return [
            self::TYPE_AMAZON_SMTP => [
                'cols' => [
                    'name' => 'required',
                    'host' => 'required',
                    'aws_access_key_id' => 'required',
                    'aws_secret_access_key' => 'required',
                    'aws_region' => 'required',
                    'smtp_username' => 'required',
                    'smtp_password' => 'required',
                    'smtp_port' => 'required',
                    'smtp_protocol' => 'required',
                    'default_from_email' => 'email',
                ],
            ],
            self::TYPE_AMAZON_API => [
                'cols' => [
                    'name' => 'required',
                    'aws_access_key_id' => 'required',
                    'aws_secret_access_key' => 'required',
                    'aws_region' => 'required',
                    'default_from_email' => 'email',
                ],
            ],
            self::TYPE_SENDGRID_SMTP => [
                'cols' => [
                    'name' => 'required',
                    'api_key' => 'required',
                    'host' => 'required',
                    'smtp_username' => 'required',
                    'smtp_password' => 'required',
                    'smtp_port' => 'required',
                    'default_from_email' => 'email',
                ],
            ],
            self::TYPE_SENDGRID_API => [
                'cols' => [
                    'name' => 'required',
                    'api_key' => 'required',
                    'default_from_email' => 'email',
                ],
            ],
            self::TYPE_MAILGUN_API => [
                'cols' => [
                    'name' => 'required',
                    'api_key' => 'required',
                    'domain' => 'required',
                    'default_from_email' => 'email',
                ],
            ],
            self::TYPE_MAILGUN_SMTP => [
                'cols' => [
                    'name' => 'required',
                    'domain' => 'required',
                    'api_key' => 'required',
                    'smtp_username' => 'required',
                    'smtp_password' => 'required',
                    'smtp_port' => 'required',
                    'smtp_protocol' => 'required',
                    'default_from_email' => 'email',
                ],
            ],
            self::TYPE_ELASTICEMAIL_API => [
                'cols' => [
                    'name' => 'required',
                    'api_key' => 'required',
                    'default_from_email' => 'email',
                ],
            ],
            self::TYPE_ELASTICEMAIL_SMTP => [
                'cols' => [
                    'name' => 'required',
                    'api_key' => 'required',
                    'host' => 'required',
                    'smtp_username' => 'required',
                    'smtp_password' => 'required',
                    'smtp_port' => 'required',
                    'default_from_email' => 'email',
                ],
            ],
            self::TYPE_SPARKPOST_API => [
                'cols' => [
                    'name' => 'required',
                    'host' => 'required',
                    'api_key' => 'required',
                    'default_from_email' => 'email',
                ],
            ],
            self::TYPE_SPARKPOST_SMTP => [
                'cols' => [
                    'name' => 'required',
                    'api_key' => 'required',
                    'host' => 'required',
                    'smtp_username' => 'required',
                    'smtp_password' => 'required',
                    'smtp_port' => 'required',
                    'smtp_protocol' => '',
                    'default_from_email' => 'email',
                ],
            ],
            self::TYPE_SMTP => [
                'cols' => [
                    'name' => 'required',
                    'host' => 'required',
                    'smtp_username' => 'required',
                    'smtp_password' => 'required',
                    'smtp_port' => 'required',
                    'smtp_protocol' => '',
                    'default_from_email' => 'email',
                    'bounce_handler_id' => '',
                    'feedback_loop_handler_id' => '',
                ],
            ],
            self::TYPE_SENDMAIL => [
                'cols' => [
                    'name' => 'required',
                    'sendmail_path' => 'required',
                    'default_from_email' => 'email',
                    'bounce_handler_id' => '',
                    'feedback_loop_handler_id' => '',
                ],
            ],
        ];
    }

    /**
     * Get select options.
     *
     * @return array
     */
    public static function getSelectOptions()
    {
        $query = self::getAll();
        $options = $query->orderBy('name')->get()->map(function ($item) {
            return ['value' => $item->uid, 'text' => $item->name];
        });

        return $options;
    }

    /**
     * Get sparkpost select options.
     *
     * @return array
     */
    public static function getSparkpostHostnameSelectOptions()
    {
        $options = [
            ['text' => trans('messages.choose'), 'value' => ''],
            ['text' => 'SparkPost Global', 'value' => 'api.sparkpost.com'],
            ['text' => 'SparkPost EU', 'value' => 'api.eu.sparkpost.com'],
        ];

        return $options;
    }

    /**
     * Get rules.
     *
     * @return string
     */
    public static function rules($type)
    {
        $rules = self::types()[$type]['cols'];
        $rules['quota_value'] = 'required|numeric';
        $rules['quota_base'] = 'required|numeric';
        $rules['quota_unit'] = 'required';

        return $rules;
    }

    /**
     * Get rules.
     *
     * @return string
     */
    public static function frontendRules($type)
    {
        $rules = self::frontendTypes()[$type]['cols'];
        $rules['quota_value'] = 'required|numeric';
        $rules['quota_base'] = 'required|numeric';
        $rules['quota_unit'] = 'required';

        return $rules;
    }

    /**
     * Get rules.
     *
     * @return string
     */
    public function getFrontendRules()
    {
        $rules = self::frontendTypes()[$this->type]['cols'];

        return $rules;
    }

    /**
     * Get rules.
     *
     * @return string
     */
    public function getRules()
    {
        $rules = self::types()[$this->type]['cols'];

        return $rules;
    }

    public function getCustomValidationError()
    {
        return [];
    }

    /**
     * Test connection.
     *
     * @return object
     */
    public function validConnection($params)
    {
        $validator = \Validator::make($params, $this->getRules(), $this->getCustomValidationError());

        // test amazon api connection
        $validator->after(function ($validator) {
            try {
                $this->test();
            } catch (\Exception $e) {
                $validator->errors()->add('connection', $e->getMessage());
            }
        });

        return $validator;
    }

    /**
     * Get configuration rules.
     *
     * @return string
     */
    public function getConfigRules()
    {
        $rules = self::types()[$this->type]['settings'];

        return $rules;
    }

    /**
     * Quota display.
     *
     * @return string
     */
    public function displayQuota()
    {
        if ($this->quota_value == -1) {
            return trans('messages.unlimited');
        }

        return $this->quota_value.'/'.$this->quota_base.' '.trans('messages.'.\Acelle\Library\Tool::getPluralPrase($this->quota_unit, $this->quota_base));
    }

    /**
     * Quota display.
     *
     * @return string
     */
    public function displayQuotaHtml()
    {
        if ($this->quota_value == -1) {
            return trans('messages.unlimited');
        }

        return '<b>'.$this->quota_value.'</b>/<b>'.$this->quota_base.' '.trans('messages.'.\Acelle\Library\Tool::getPluralPrase($this->quota_unit, $this->quota_base)).'</b>';
    }

    /**
     * Select options for aws region.
     *
     * @return array
     */
    public static function awsRegionSelectOptions()
    {
        return [
            ['value' => '', 'text' => trans('messages.choose')],
            ['value' => 'us-east-1', 'text' => 'US East (N. Virginia)', 'host' => 'email-smtp.us-east-1.amazonaws.com'],
            ['value' => 'us-east-2', 'text' => 'US East (Ohio)', 'host' => 'email-smtp.us-east-2.amazonaws.com'],
            ['value' => 'us-west-1', 'text' => 'US West (N. California)', 'host' => 'email-smtp.us-west-1.amazonaws.com'],
            ['value' => 'us-west-2', 'text' => 'US West (Oregon)', 'host' => 'email-smtp.us-west-2.amazonaws.com'],
            ['value' => 'ap-south-1', 'text' => 'Asia Pacific (Mumbai)', 'host' => 'email-smtp.ap-south-1.amazonaws.com'],
            ['value' => 'ap-southeast-1', 'text' => 'Asia Pacific (Singapore)', 'host' => 'email-smtp.ap-southeast-1.amazonaws.com'],
            ['value' => 'ap-southeast-2', 'text' => 'Asia Pacific (Sydney)', 'host' => 'email-smtp.ap-southeast-2.amazonaws.com'],
            ['value' => 'ap-northeast-1', 'text' => 'Asia Pacific (Tokyo)', 'host' => 'email-smtp.ap-northeast-1.amazonaws.com'],
            ['value' => 'ap-northeast-2', 'text' => 'Asia Pacific (Seoul)', 'host' => 'email-smtp.ap-northeast-2.amazonaws.com'],
            ['value' => 'ca-central-1', 'text' => 'Canada (Central)', 'host' => 'email-smtp.ca-central-1.amazonaws.com'],
            ['value' => 'eu-central-1', 'text' => 'Europe (Frankfurt)', 'host' => 'email-smtp.eu-central-1.amazonaws.com'],
            ['value' => 'eu-west-1', 'text' => 'EU (Ireland)', 'host' => 'email-smtp.eu-west-1.amazonaws.com'],
            ['value' => 'eu-west-2', 'text' => 'Europe (London)', 'host' => 'email-smtp.eu-west-2.amazonaws.com'],
            ['value' => 'eu-west-3', 'text' => 'Europe (Paris)', 'host' => 'email-smtp.eu-west-3.amazonaws.com'],
            ['value' => 'eu-north-1', 'text' => 'Europe (Stockholm)', 'host' => 'email-smtp.eu-north-1.amazonaws.com'],
            ['value' => 'me-south-1', 'text' => 'Middle East (Bahrain)', 'host' => 'email-smtp.me-south-1.amazonaws.com'],
            ['value' => 'sa-east-1', 'text' => 'South America (São Paulo)', 'host' => 'email-smtp.sa-east-1.amazonaws.com'],
        ];
    }

    /**
     * Select options for aws region.
     *
     * @return array
     */
    public static function mailgunRegionSelectOptions()
    {
        return [
            ['value' => '', 'text' => trans('messages.choose')],
            ['value' => 'https://api.mailgun.net', 'text' => 'US/Global Server'],
            ['value' => 'https://api.eu.mailgun.net', 'text' => 'EU Server'],
        ];
    }

    /**
     * Disable sending server.
     *
     * @return array
     */
    public function disable()
    {
        $this->status = 'inactive';
        $this->save();
    }

    /**
     * Enable sending server.
     *
     * @return array
     */
    public function enable()
    {
        $this->status = 'active';
        $this->save();
    }

    /**
     * Check if sending server supports custom ReturnPath header (used for bounced/feedback handling).
     *
     * @return bool
     */
    public function allowCustomReturnPath()
    {
        return  $this->type == 'smtp' || $this->type == 'sendmail' || $this->type == 'php-mail';
    }

    /**
     * Get all active items.
     *
     * @return collect
     */
    public function scopeActive($query)
    {
        return $query->where('status', '=', self::STATUS_ACTIVE);
    }

    /**
     * Get all active system items.
     *
     * @return collect
     */
    public function scopeSystem($query)
    {
        return $query->active()->whereNull('customer_id');
    }

    /**
     * Add customer action log.
     */
    public function log($name, $customer, $add_datas = [])
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
        ];

        $data = array_merge($data, $add_datas);

        Log::create([
            'customer_id' => $customer->id,
            'type' => 'sending_server',
            'name' => $name,
            'data' => json_encode($data),
        ]);
    }

    /**
     * Send a test email for the sending server.
     */
    public function sendTestEmail($params)
    {
        /*
         * Required keys include
         *     + from_email
         *     + to_email
         *     + subject
         *     + plain
         */
        MailLog::info(sprintf('Sending test email to %s for sending server `%s`', $params['to_email'], $this->name));
        $message = new ExtendedSwiftMessage();
        $msgId = StringHelper::generateMessageId(StringHelper::getDomainFromEmail($params['from_email']));
        $message->setId($msgId);
        $message->getHeaders()->addTextHeader('X-Acelle-Message-Id', $msgId); // this header is required for SendGrid API sending server
        $message->setContentType('text/plain; charset=utf-8');
        $message->setSubject($params['subject']);
        $message->setFrom($params['from_email']);
        $message->setTo($params['to_email']);
        $message->setReplyTo($params['from_email']);
        // $message->setEncoder(\Swift_Encoding::get8bitEncoding());
        $message->setEncoder(new \Swift_Mime_ContentEncoder_PlainContentEncoder('8bit'));
        $message->addPart($params['plain'], 'text/plain');

        $this->setupBeforeSend($params['from_email']);
        $this->send($message);

        return true;
    }

    /**
     * Check if the sending server is ElasticEmailAPI or ElasticEmailSmtp.
     *
     * @return bool
     */
    public function isElasticEmailServer()
    {
        return $this->type == 'elasticemail-api' || $this->type == 'elasticemail-smtp';
    }

    /**
     * Get all sub-account supported sending server types.
     *
     * @return array
     */
    public static function getSubAccountTypes()
    {
        return [
            'sendgrid-api',
            'sendgrid-smtp',
        ];
    }

    public function setSubAccount($subAccount)
    {
        $this->subAccount = $subAccount;
    }

    /**
     * Get sending server select2 select options.
     *
     * @return array
     */
    public static function select2($request)
    {
        $data = ['items' => [], 'more' => true];

        $query = self::getAll();
        if (isset($request->q)) {
            $keyword = $request->q;
            $query = $query->where(function ($q) use ($keyword) {
                $q->orwhere('sending_servers.name', 'like', '%'.$keyword.'%');
            });
        }

        // plan
        if ($request->plan_uid) {
            $plan = \Acelle\Model\Plan::findByUid($request->plan_uid);
            $existIds = $plan->plansSendingServers()->pluck('sending_server_id')->toArray();
        }

        foreach ($query->limit(20)->get() as $server) {
            if ($request->plan_uid && in_array($server->id, $existIds)) {
                $data['items'][] = [
                    'id' => $server->uid,
                    'text' => $server->name.' ('.trans('messages.sending_server.added').')'.'|||'.trans('messages.'.$server->type),
                    'disabled' => true,
                ];
            } else {
                $data['items'][] = ['id' => $server->uid, 'text' => $server->name.'|||'.trans('messages.'.$server->type)];
            }
        }

        return json_encode($data);
    }

    /**
     * Get sending server select2 select options.
     *
     * @return array
     */
    public static function adminSelect2($request)
    {
        $data = ['items' => [], 'more' => true];

        $query = self::getAll()->whereNull('customer_id');
        if (isset($request->q)) {
            $keyword = $request->q;
            $query = $query->where(function ($q) use ($keyword) {
                $q->orwhere('sending_servers.name', 'like', '%'.$keyword.'%');
            });
        }

        // plan
        if ($request->plan_uid) {
            $plan = \Acelle\Model\Plan::findByUid($request->plan_uid);
            $existIds = $plan->plansSendingServers()->pluck('sending_server_id')->toArray();
        }

        foreach ($query->limit(20)->get() as $server) {
            if ($request->plan_uid && in_array($server->id, $existIds)) {
                $data['items'][] = [
                    'id' => $server->uid,
                    'text' => $server->name.' ('.trans('messages.sending_server.added').')'.'|||'.trans('messages.'.$server->type),
                    'disabled' => true,
                ];
            } else {
                $type = ($server->mapType()->isExtended()) ? $server->mapType()->getTypeName() : trans('messages.'.$server->type);
                $data['items'][] = ['id' => $server->uid, 'text' => $server->name.'|||'.$type ];
            }
        }

        return json_encode($data);
    }

    /**
     * Get sending limit types.
     *
     * @return array
     */
    public static function sendingLimitValues()
    {
        return [
            'unlimited' => [
                'quota_value' => -1,
                'quota_base' => -1,
                'quota_unit' => 'day',
            ],
            '100_per_minute' => [
                'quota_value' => 100,
                'quota_base' => 1,
                'quota_unit' => 'minute',
            ],
            '1000_per_hour' => [
                'quota_value' => 1000,
                'quota_base' => 1,
                'quota_unit' => 'hour',
            ],
            '10000_per_day' => [
                'quota_value' => 10000,
                'quota_base' => 1,
                'quota_unit' => 'day',
            ],
        ];
    }

    /**
     * Get sending limit select options.
     *
     * @return array
     */
    public function getSendingLimitSelectOptions()
    {
        $options = [];
        $current = trans('messages.sending_servers.sending_limit.phrase', [
            'quota_value' => number_with_delimiter($this->quota_value, $precision = 0),
            'quota_base' => number_with_delimiter($this->quota_base, $precision = 0),
            'quota_unit' => $this->quota_unit,
        ]);
        if ($this->quota_value == -1) {
            $current = trans('messages.sending_server.quota.unlimited');
        }

        $exist = false;
        foreach (self::sendingLimitValues() as $key => $data) {
            $wording = trans('messages.sending_servers.sending_limit.phrase', [
                'quota_value' => number_with_delimiter($data['quota_value'], $precision = 0),
                'quota_base' => number_with_delimiter($data['quota_base'], $precision = 0),
                'quota_unit' => $data['quota_unit'],
            ]);

            if ($data['quota_value'] == -1) {
                $wording = trans('messages.sending_server.quota.unlimited');
            }

            $options[] = ['text' => $wording, 'value' => $key];

            if ($wording == $current) {
                $exist = true;
                $this->setOption('sending_limit', $key);
            }
        }

        // exist
        if (!$exist) {
            $options[] = ['text' => $current, 'value' => 'current'];
            $this->setOption('sending_limit', 'current');
        }

        // Custom
        $options[] = ['text' => trans('messages.sending_servers.quota.custom'), 'value' => 'custom'];

        return $options;
    }

    /**
     * Default options.
     *
     * @return array
     */
    public static function defaultOptions()
    {
        return [
            'domains' => [],
            'emails' => [],
            'allow_unverified_from_email' => 'no',
            'allow_verify_domain_remotely' => 'no',
            'allow_verify_email_remotely' => 'no',
        ];
    }

    /**
     * Get options.
     *
     * @return array
     */
    public function getOptions()
    {
        $savedOptions = isset($this->options) ? json_decode($this->options, true) : [];

        return array_merge(self::defaultOptions(), $savedOptions);
    }

    /**
     * Get option.
     *
     * @return array
     */
    public function getOption($name)
    {
        $options = $this->getOptions();

        $value = isset($options[$name]) ? $options[$name] : null;

        // default value
        if (!$value) {
            // default verification email
            if ($name == 'custom_verification_email') {
                $value = trans('messages.sending_server.default_email_verification.content');
                ;
            }

            // default verification email
            if ($name == 'custom_verification_email_subject') {
                $value = trans('messages.sending_server.default_email_verification.subject');
                ;
            }
        }


        return $value;
    }

    /**
     * Get options.
     *
     * @return array
     */
    public function setOptions($options)
    {
        $savingOptions = $this->getOptions();
        foreach ($options as $key => $option) {
            $savingOptions[$key] = $option;
        }

        $this->options = json_encode($savingOptions);
    }

    /**
     * Get options.
     *
     * @return array
     */
    public function setOption($name, $value)
    {
        if (!isset($this->options)) {
            $options = [];
        } else {
            $options = json_decode($this->options, true);
        }

        $options[$name] = $value;

        $this->options = json_encode($options);
    }

    /**
     * Get Mailgun domains info.
     *
     * @return array
     */
    public function getMailgunDomainInfo()
    {
        return [
            [
                'domain' => 'acellemail.com',
                'created_at' => \Carbon\Carbon::now()->subDay(2),
            ],
            [
                'domain' => 'bolero.vn',
                'created_at' => \Carbon\Carbon::now()->subDay(13),
            ],
        ];
    }

    /**
     * Get local identity info.
     *
     * @return array
     */
    public function getLocalIdentityInfo()
    {
        return [
            [
                'type' => 'domain',
                'name' => 'acellemail.com',
                'created_at' => \Carbon\Carbon::now()->subDay(2),
            ],
            [
                'type' => 'domain',
                'name' => 'bolero.vn',
                'created_at' => \Carbon\Carbon::now()->subDay(13),
            ],
            [
                'type' => 'email',
                'name' => 'system@acellemail.com',
                'created_at' => \Carbon\Carbon::now()->subDay(13),
            ],
        ];
    }

    /**
     * Add domain.
     *
     * @return array
     */
    public function addIdentity($domain)
    {
        $identityStore = $this->getIdentityStore();
        $identityStore->add([ $domain => ['VerificationStatus' => true ]]);
        $this->setOption('identities', $identityStore->get());
        $this->save();
    }

    /**
     * Add domain.
     *
     * @return array
     */
    public function removeIdentity($identity)
    {
        $identityStore = $this->getIdentityStore();
        $identityStore->remove($identity);
        $this->setOption('identities', $identityStore->get());
        $this->save();
    }

    /**
     * Check if domain is enabled.
     *
     * @return array
     */
    public function isDomainEnabled($domain)
    {
        $domains = $this->getDomains();

        return in_array($domain, $domains);
    }

    /**
     * Check if emails is enabled.
     *
     * @return array
     */
    public function isEmailEnabled($email)
    {
        $emails = $this->getEmails();

        return in_array($email, $emails);
    }

    /**
     * Check if domain is enabled.
     *
     * @return array
     */
    public function isIdentityEnabled($type, $value)
    {
        $values = $this->getOption($type.'s');

        return in_array($values, $value);
    }

    /**
     * Allow user to verify his/her own sending domain against Acelle Mail.
     *
     * @return bool
     */
    public function allowVerifyingOwnDomains()
    {
        $options = json_decode($this->options, true);

        if (is_null($options)) {
            return false;
        }

        return array_key_exists('allow_verify_domain_against_acelle', $options) && $options['allow_verify_domain_against_acelle'] == 'yes';
    }

    /**
     * Allow user to verify his/her own sending domain against Acelle Mail.
     *
     * @return bool
     */
    public function allowVerifyingOwnEmails()
    {
        $options = json_decode($this->options, true);

        if (is_null($options)) {
            return false;
        }

        return array_key_exists('allow_verify_email_against_acelle', $options) && $options['allow_verify_email_against_acelle'] == 'yes';
    }

    /**
     * Allow user to verify his/her own emails against AWS.
     *
     * @return bool
     */
    public function allowVerifyingOwnDomainsRemotely()
    {
        $options = json_decode($this->options, true);

        if (is_null($options)) {
            return false;
        }

        return array_key_exists('allow_verify_domain_remotely', $options) && $options['allow_verify_domain_remotely'] == 'yes';
    }

    /**
     * Allow user to verify his/her own emails against AWS.
     *
     * @return bool
     */
    public function allowVerifyingOwnEmailsRemotely()
    {
        $options = json_decode($this->options, true);

        if (is_null($options)) {
            return false;
        }

        return array_key_exists('allow_verify_email_remotely', $options) && $options['allow_verify_email_remotely'] == 'yes';
    }

    /**
     * Allow user send from unverified FROM email address.
     *
     * @return bool
     */
    public function allowUnverifiedFromEmailAddress()
    {
        return true;
        $options = json_decode($this->options, true);

        if (is_null($options)) {
            return false;
        }

        return array_key_exists('allow_unverified_from_email', $options) && $options['allow_unverified_from_email'] == 'yes';
    }

    /**
     * Check the sending server settings, make sure it does work.
     *
     * @return bool
     */
    public function test()
    {
        return true;
    }

    /**
     * Get all verified identities.
     *
     * @return array
     */
    public function verifiedIdentitiesDroplist($keyword = null)
    {
        $droplist = [];
        $topList = [];
        $bottomList = [];

        if (!$keyword) {
            $keyword = '###';
        }

        foreach ($this->getVerifiedIdentities() as $item) {
            // check if email
            if (extract_email($item) !== null) {
                $email = extract_email($item);
                if (strpos(strtolower($email), $keyword) === 0) {
                    $topList[] = [
                            'text' => extract_name($item),
                            'value' => $email,
                            'desc' => str_replace($keyword, '<span class="text-semibold text-primary"><strong>'.$keyword.'</strong></span>', $email),
                        ];
                } else {
                    $bottomList[] = [
                            'text' => extract_name($item),
                            'value' => $email,
                            'desc' => $email,
                        ];
                }
            } else { // domains are alse
                $dKey = explode('@', $keyword);
                $dKey = isset($dKey[1]) ? $dKey[1] : null;
                // if ( (!isset($dKey) || $dKey == '') || ($dKey && strpos(strtolower($item), $dKey) === 0 )) {
                $topList[] = [
                            'text' => '****@'.str_replace($dKey, '<span class="text-semibold text-primary"><strong>'.$dKey.'</strong></span>', $item),
                            'subfix' => $item,
                            'desc' => null,
                        ];
                // }
            }
        }

        $droplist = array_merge($topList, $bottomList);

        return $droplist;
    }

    /**
     * Delete sending server.
     */
    public function doDelete()
    {
        $plans = $this->plans;

        // delete
        $this->delete();

        // check all plans status
        foreach ($plans as $plan) {
            $plan->checkStatus();
        }
    }

    public function updateIdentitiesList($selected)
    {
        // For now, it is for Amazon only
        $options = $this->getOptions();
        if (!array_key_exists('identities', $options)) {
            return;
        }

        $selectedEmails = array_key_exists('emails', $selected) ? $selected['emails'] : [];
        $selectedDomains = array_key_exists('domains', $selected) ? $selected['domains'] : [];
        $identityStore = new IdentityStore($options['identities']);
        $identityStore->select(array_merge($selectedEmails, $selectedDomains));

        $options['identities'] = $identityStore->get();
        $this->setOptions($options);
    }

    public function getVerifiedIdentities()
    {
        // by default, only return SELECTED | VERIRIED | NON-PRIVATE identities
        $filtered = $this->getIdentityStore()->get(['Selected' => true, 'UserId' => null, 'VerificationStatus' => 'Success']);
        return array_keys($filtered);
    }

    public function getIdentityStore(): IdentityStore
    {
        $options = $this->getOptions();
        $identityStore = new IdentityStore(array_key_exists('identities', $options) ? $options['identities'] : []);
        return $identityStore;
    }

    public function mapType()
    {
        return self::mapServerType($this);
    }

    /**
     * Check an identity (email or domain) if it is verified against AWS.
     *
     * @return bool
     */
    public function verifyIdentity($identity)
    {
        $this->syncIdentities();
        $verifiedIdentities = array_keys($this->getIdentityStore()->get(['VerificationStatus' => IdentityStore::VERIFICATION_STATUS_SUCCESS]));
        return in_array($identity, $verifiedIdentities);
    }

    public function sendWithDefaultFromAddress($message, $params = [])
    {
        if (empty($this->from_name)) {
            $message->setFrom([ $this->from_name => $this->from_address ]);
        } else {
            $message->setFrom($this->from_address);
        }

        return  $this->send($message, $params);
    }

    public function setDefaultFromEmailAddress()
    {
        if (!empty($this->default_from_email)) {
            return;
        }

        $identityStore = $this->getIdentityStore();
        $names = array_keys($identityStore->get(['VerificationStatus' => 'Success']));

        $emails = array_values(array_filter($names, function ($name) {
            return checkEmail($name);
        }));
        $domains = array_values(array_filter($names, function ($name) {
            return !checkEmail($name);
        }));

        $default = null;

        if (!empty($domains)) {
            $default = 'noreply@'.$domains[0];
        } elseif (!empty($emails)) {
            $default = $emails[0];
        }

        if (!is_null($default)) {
            $this->default_from_email = $default;
            $this->save();
        }
    }

    public function getSpfHost()
    {
        return null;
    }

    public function isExtended()
    {
        return false;
    }

    public function getIconUrl()
    {
        return null;
    }

    public function getDefaultName()
    {
        return null;
    }

    public static function createFromArray($params)
    {
        $server = new self();
        $server->fill($params);
        $server = $server->mapType();

        // validation
        $validator = $server->validConnection($params);

        if ($validator->fails()) {
            return [$validator, $server]; // IMPORTANT, $server instance (not saved) is required by parent controller
        }

        if (isset($params['admin_id'])) {
            $server->admin_id = $params['admin_id'];
        }
        if (isset($params['customer_id'])) {
            $server->customer_id = $params['customer_id'];
        }

        $server->status = self::STATUS_ACTIVE;

        // default name
        if (!$server->name) {
            $server->name = $server->getDefaultName() ?: trans('messages.' . $server->type);
        }

        // default sever quota
        if (!$server->quota_value) {
            $server->quota_value = 1000;
            $server->quota_base = 1;
            $server->quota_unit = 'hour';
            $options = ['sending_limit' => '1000_per_hour'];
            $server->options = json_encode($options);
        }

        // bounce / feedback hanlder nullable
        if (!isset($params['bounce_handler_id'])) {
            $server->bounce_handler_id = null;
        }
        if (!isset($params['feedback_loop_handler_id'])) {
            $server->feedback_loop_handler_id = null;
        }

        $server->save();

        return [ $validator, $server ];
    }

    public function getTypeName()
    {
        return trans('messages.' . $this->type);
    }

    public function allowOtherSendingDomains()
    {
        return false;
    }

    public function setQuotaSettings(int $value, string $periodUnit, int $periodBase)
    {
        $this->quota_base = $periodBase;
        $this->quota_unit = $periodUnit;
        $this->quota_value = $value;
    }

    /*** IMPLEMENTATION OF HasQuotaInterface ***/
    public function getQuotaSettings($name): ?array
    {
        $quota = [];

        if ($this->quota_value != QuotaManager::QUOTA_UNLIMITED) {
            $quota[] = [
                'name' => "Server's sending limit of {$this->quota_value} per {$this->quota_base} {$this->quota_unit}",
                'period_unit' => $this->quota_unit,
                'period_value' => $this->quota_base,
                'limit' => $this->quota_value,
            ];
        }

        return $quota;
    }
}
