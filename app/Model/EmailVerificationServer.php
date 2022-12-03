<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use JsonPath\JsonObject;
use Acelle\Library\Log as MailLog;
use Acelle\Library\Traits\HasUid;
use Acelle\Library\Traits\HasQuota;
use Acelle\Library\Contracts\HasQuota as HasQuotaInterface;
use Exception;
use Acelle\Library\QuotaManager;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;
use Acelle\Library\Everification\Emailable;

class EmailVerificationServer extends Model implements HasQuotaInterface
{
    use HasUid;
    use HasQuota;

    // set the table name
    protected $table = 'email_verification_servers';

    // Logger
    protected $logger;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const WAIT = 30;

    protected $fillable = ['type', 'name'];

    private const OTHERWISE = '*';

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

    public function initEverificationService()
    {
        switch ($this->type) {
            case 'emailable.com':
                $service = new Emailable($this->getOptions(), $this->logger());
                return $service;
            default:
                return null;
        }
    }

    /**
     * Verify email address.
     *
     * @return mixed
     */
    public function verify($email)
    {
        // Retrieve the email verification service
        $service = $this->initEverificationService();

        if ($service) {
            return $service->verify($email);
        }

        // @deprecated enforce the verification speed limit
        $options = $this->getOptions();
        $limit = "[{$options['limit_value']} / {$options['limit_base']} {$options['limit_unit']}]";

        // retrieve the service settings
        $config = $this->getConfig();
        $options = $this->getOptions();
        $client = new Client(['verify' => false]);

        // build the request URI
        $uri = $config['uri'];
        $uri = str_replace('{EMAIL}', $email, $uri);
        $uri = array_key_exists('api_key', $options) ? str_replace('{API_KEY}', $options['api_key'], $uri) : $uri;
        $uri = array_key_exists('api_secret_key', $options) ? str_replace('{API_SECRET_KEY}', $options['api_secret_key'], $uri) : $uri;
        $uri = array_key_exists('username', $options) ? str_replace('{USERNAME}', $options['username'], $uri) : $uri;
        $uri = array_key_exists('password', $options) ? str_replace('{PASSWORD}', $options['password'], $uri) : $uri;

        // build the request URI
        if ($config['request_type'] == 'POST') {
            $postdata = $config['post_data'];
            $postdata = str_replace('{EMAIL}', $email, $postdata);
            foreach ($config['fields'] as $field) {
                if (array_key_exists($field, $options)) {
                    $postdata = str_replace('{'.strtoupper($field).'}', $options[$field], $postdata);
                }
            }

            $headers = $config['post_headers'];
            foreach ($headers as $header => $value) {
                foreach ($config['fields'] as $field) {
                    if (array_key_exists($field, $options)) {
                        $value = str_replace('{'.strtoupper($field).'}', $options[$field], $value);
                        $headers[$header] = $value;
                    }
                }
            }

            // make POST request
            $response = $client->request(
                $config['request_type'],
                $uri,
                [ 'headers' => $headers, 'body' => $postdata, 'verify' => false]
            );
        } else { // GET request
            // actually request to the service
            $response = $client->request($config['request_type'], $uri, ['verify' => false]);
        }

        // fetch the result
        $raw = (string)$response->getBody();

        if ($raw == 'error_credit') {
            throw new Exception('No verification credits available for service '.$this->type);
        }

        // PLAIN RESPONSE
        if (array_key_exists('response_type', $config) && $config['response_type'] == 'plain') {
            if (!array_key_exists($raw, $config['result_map']) && array_key_exists(self::OTHERWISE, $config['result_map'])) {
                $mapped = $config['result_map'][self::OTHERWISE];
            } elseif (!array_key_exists($raw, $config['result_map'])) {
                throw new \Exception('Unexpected result from verification service: '.$raw);
            } else {
                $mapped = $config['result_map'][$raw];
            }

            return [$mapped, $response];
        }

        // JSON RESPONSE
        $jsonObject = new JsonObject($raw);

        try {
            $result = $jsonObject->get($config['result_xpath']);

            if (empty($result)) {
                throw new Exception('Empty response after parse XPATH');
            }

            $result = $result[0];
        } catch (\Throwable $ex) {
            $message = sprintf("[Verification Server #%s, %s] Cannot parse result with XPATH: `%s`\n", $this->id, $this->type, $config['result_xpath']);
            $message .= sprintf("Raw:\n%s\n", $raw);
            $message .= sprintf("Error: %s", $ex->getMessage());

            throw new Exception($message);
        }

        // map the result value to those of Acelle Mail
        $result = is_bool($result) ? json_encode($result) : $result;
        if (!array_key_exists($result, $config['result_map'])) {
            throw new \Exception('Unexpected result from verification service: '.$raw);
        }
        $mapped = $config['result_map'][$result];

        return [$mapped, $response];
    }

    /**
     * Find the configuration settings for a given verification service.
     *
     * @return mixed
     */
    public function getConfig()
    {
        $configs = \Config::get('verification.services');
        foreach ($configs as $config) {
            if ($config['id'] == $this->type) {
                return $config;
            }
        }

        throw new \Exception('Cannot find settings for verification service '.$this->type);
    }

    /**
     * Items per page.
     *
     * @var array
     */
    public static $itemsPerPage = 25;

    /**
     * Filter items.
     *
     * @return collect
     */
    public static function filter($request)
    {
        $user = $request->user();
        $query = self::select('email_verification_servers.*');

        // Keyword
        if (!empty(trim($request->keyword))) {
            foreach (explode(' ', trim($request->keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('email_verification_servers.name', 'like', '%'.$keyword.'%')
                        ->orWhere('email_verification_servers.type', 'like', '%'.$keyword.'%');
                });
            }
        }

        // filters
        $filters = $request->all();
        if (!empty($filters)) {
            if (!empty($filters['type'])) {
                $query = $query->where('email_verification_servers.type', '=', $filters['type']);
            }
        }

        // Other filter
        if (!empty($request->customer_id)) {
            $query = $query->where('email_verification_servers.customer_id', '=', $request->customer_id);
        }

        if (!empty($request->admin_id)) {
            $query = $query->where('email_verification_servers.admin_id', '=', $request->admin_id);
        }

        // remove customer sending servers
        if (!empty($request->no_customer)) {
            $query = $query->whereNull('customer_id');
        }

        return $query;
    }

    /**
     * Search items.
     *
     * @return collect
     */
    public static function search($request)
    {
        $query = self::filter($request);

        if (!empty($request->sort_order)) {
            $query = $query->orderBy($request->sort_order, $request->sort_direction);
        }

        return $query;
    }

    /**
     * Get server type select options.
     *
     * @return array
     */
    public static function typeSelectOptions()
    {
        $services = config('verification.services');
        $options = [];
        foreach ($services as $service) {
            $options[] = ['value' => $service['id'], 'text' => $service['name']];
        }

        return $options;
    }

    /**
     * Get campaign validation rules.
     */
    public function rules()
    {
        $rules = array(
            'name' => 'required',
            'type' => 'required',
            'options.limit_value' => 'required',
            'options.limit_base' => 'required',
            'options.limit_unit' => 'required',
        );

        if ($this->type) {
            foreach ($this->getConfig()['fields'] as $field) {
                $rules['options.'.$field] = 'required';
            }
        }

        return $rules;
    }

    /**
     * Frequency time unit options.
     *
     * @return array
     */
    public static function quotaTimeUnitOptions()
    {
        return [
            ['value' => 'minute', 'text' => trans('messages.minute')],
            ['value' => 'hour', 'text' => trans('messages.hour')],
            ['value' => 'day', 'text' => trans('messages.day')],
        ];
    }

    /**
     * Server status select options.
     *
     * @return array
     */
    public static function statusSelectOptions()
    {
        return [
            ['value' => self::STATUS_ACTIVE, 'text' => trans('messages.email_verification_server_status_'.self::STATUS_ACTIVE)],
            ['value' => self::STATUS_INACTIVE, 'text' => trans('messages.email_verification_server_status_'.self::STATUS_INACTIVE)],
        ];
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
     * Get all options.
     *
     * @return object
     */
    public function getOptions()
    {
        return !isset($this->options) ? [] : json_decode($this->options, true);
    }

    /**
     * Disable verification server.
     *
     * @return array
     */
    public function disable()
    {
        $this->status = self::STATUS_INACTIVE;
        $this->save();
    }

    /**
     * Enable verification server.
     *
     * @return array
     */
    public function enable()
    {
        $this->status = self::STATUS_ACTIVE;
        $this->save();
    }

    /**
     * Get all active items.
     *
     * @return collect
     */
    public static function getAllActive()
    {
        return self::where('status', '=', SendingServer::STATUS_ACTIVE);
    }

    /**
     * Get all active system items.
     *
     * @return collect
     */
    public static function getAllAdminActive()
    {
        return self::getAllActive()->whereNotNull('admin_id');
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
            'type' => 'email_verification_server',
            'name' => $name,
            'data' => json_encode($data),
        ]);
    }

    public function getSpeedLimitString()
    {
        $options = $this->getOptions();

        return "{$options['limit_value']} / {$options['limit_base']} {$options['limit_unit']}";
    }

    public function getTypeName()
    {
        try {
            $service = $this->getConfig();

            return $service['name'];
        } catch (\Exception $ex) {
            return 'Error: Config missing!';
        }
    }

    public function setQuotaSettings(string $name, int $value, string $periodUnit, int $periodValue)
    {
        // $name is always 'verify'
        if ($name != 'verify') {
            throw "Invalid quota name for email verification server";
        }

        $options = $this->getOptions();
        $options['limit_base'] = $periodValue;
        $options['limit_unit'] = $periodUnit;
        $options['limit_value'] = $value;
        $this->options = json_encode($options);
    }

    /***   IMPLEMENTATION OF HasQuotaInterface ***/
    public function getQuotaSettings($name): ?array
    {
        // always 'verify'
        $quota = [];
        $options = $this->getOptions();

        if ($options['limit_value'] != QuotaManager::QUOTA_UNLIMITED) {
            $quota[] = [
                'name' => "Verification credits limit of {$options['limit_value']} per {$options['limit_base']} {$options['limit_unit']}",
                'period_unit' => $options['limit_unit'],
                'period_value' => $options['limit_base'],
                'limit' => $options['limit_value'],
            ];
        }

        // Take limits from sending credits
        $credits = '????';
        if (false && $credits != QuotaManager::QUOTA_UNLIMITED) {
            $quota[] = [
                'name' => "Plan's verification limit",
                'period_unit' => '?',
                'period_value' => '?',
                'limit' => $redits
            ];
        }

        return $quota;
    }

    public function logger()
    {
        if (is_null($this->logger)) {
            $formatter = new LineFormatter("[%datetime%] %channel%.%level_name%: %message%\n");
            $logfile = storage_path('logs/' . php_sapi_name() . "/everification-{$this->uid}-{$this->type}.log");
            $stream = new RotatingFileHandler($logfile, 0, 'debug');
            $stream->setFormatter($formatter);
            $pid = getmypid();
            $logger = new Logger($pid);
            $logger->pushHandler($stream);

            // Set
            $this->logger = $logger;
        }

        return $this->logger;
    }
}
