<?php

/**
 * Customer class.
 *
 * Model class for customer
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
use Carbon\Carbon;
use Acelle\Cashier\Cashier;
use Acelle\Model\Subscription;
use Acelle\Model\SendingDomain;
use Acelle\Model\Source;
use Acelle\Model\WooCommerce;
use Acelle\Model\Lazada;
use Acelle\Model\MailList;
use DateTime;
use DateTimeZone;
use Exception;
use DB;
use Acelle\Library\Facades\Billing;
use Acelle\Library\AutoBillingData;
use File;
use Acelle\Library\Traits\TrackJobs;
use Acelle\Jobs\ImportBlacklistJob;
use Acelle\Library\Traits\HasUid;
use Acelle\Library\QuotaManager;

class Customer extends Model
{
    use TrackJobs;
    use HasUid;

    protected $quotaTracker;

    // Plan status
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_ACTIVE = 'active';

    public const BASE_DIR = 'app/customers'; // storage/customers/000000
    public const ATTACHMENTS_DIR = 'home/attachments';  // storage/customers/000000/home/files
    public const TEMPLATES_DIR = 'home/templates';  // storage/customers/000000/home/files
    public const PRODUCT_DIR = 'home/products';
    public const LOGS_DIR = 'home/logs/';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'timezone', 'language_id', 'color_scheme', 'text_direction', 'menu_layout', 'theme_mode'
    ];

    /**
     * Associations.
     *
     * @var object | collect
     */
    public function subscription()
    {
        return $this->hasOne('Acelle\Model\Subscription')
            ->where('status', '!=', Subscription::STATUS_ENDED)
            ->orderBy('created_at', 'desc');
    }

    public function contact()
    {
        return $this->belongsTo('Acelle\Model\Contact');
    }

    public function subscriptions()
    {
        return $this->hasMany('Acelle\Model\Subscription');
    }

    public function mailLists()
    {
        return $this->hasMany('Acelle\Model\MailList');
    }

    public function user()
    {
        return $this->hasOne('Acelle\Model\User');
    }

    public function admin()
    {
        return $this->belongsTo('Acelle\Model\Admin');
    }

    public function lists()
    {
        return $this->hasMany('Acelle\Model\MailList');
    }

    public function templates()
    {
        return $this->hasMany('Acelle\Model\Template');
    }

    public function language()
    {
        return $this->belongsTo('Acelle\Model\Language');
    }

    public function campaigns()
    {
        return $this->hasMany('Acelle\Model\Campaign');
    }

    public function sentCampaigns()
    {
        return $this->hasMany('Acelle\Model\Campaign')->where('status', '=', 'done')->orderBy('created_at', 'desc');
    }

    public function subscribers()
    {
        return $this->hasManyThrough('Acelle\Model\Subscriber', 'Acelle\Model\MailList');
    }

    public function logs()
    {
        return $this->hasMany('Acelle\Model\Log')->orderBy('created_at', 'desc');
    }

    public function trackingLogs()
    {
        return $this->hasMany('Acelle\Model\TrackingLog')->orderBy('created_at', 'asc');
    }

    public function automation2s()
    {
        return $this->hasMany('Acelle\Model\Automation2');
    }

    public function activeAutomation2s()
    {
        return $this->hasMany('Acelle\Model\Automation2')->where('status', Automation2::STATUS_ACTIVE);
    }

    public function sendingDomains()
    {
        return $this->hasMany('Acelle\Model\SendingDomain');
    }

    public function emailVerificationServers()
    {
        return $this->hasMany('Acelle\Model\EmailVerificationServer');
    }

    public function activeEmailVerificationServers()
    {
        return $this->emailVerificationServers()->where('status', '=', EmailVerificationServer::STATUS_ACTIVE);
    }

    public function blacklists()
    {
        return $this->hasMany('Acelle\Model\Blacklist');
    }

    // Only direct senders children
    public function senders()
    {
        return $this->hasMany('Acelle\Model\Sender');
    }

    // tracking domain
    public function trackingDomains()
    {
        return $this->hasMany('Acelle\Model\TrackingDomain');
    }

    public function products()
    {
        return $this->hasMany('Acelle\Model\Product');
    }

    public function invoices()
    {
        return $this->hasMany('Acelle\Model\Invoice');
    }

    public function forms()
    {
        return $this->hasMany('Acelle\Model\Form');
    }

    public function websites()
    {
        return $this->hasMany('Acelle\Model\Website');
    }

    /**
     * Get user sources.
     */
    public function sources()
    {
        return $this->hasmany('Acelle\Model\Source');
    }

    // billing addresses
    public function billingAddresses()
    {
        return $this->hasMany('Acelle\Model\BillingAddress');
    }

    public function getBasePath($path = null)
    {
        $base = storage_path(join_paths(self::BASE_DIR, $this->uid)); // storage/app/customers/000000/

        if (!\File::exists($base)) {
            \File::makeDirectory($base, 0777, true, true);
        }

        return join_paths($base, $path);
    }

    public function getTemplatesPath($path = null)
    {
        $base = $this->getBasePath(self::TEMPLATES_DIR);

        if (!\File::exists($base)) {
            \File::makeDirectory($base, 0777, true, true);
        }

        return join_paths($base, $path);
    }

    public function getProductsPath($path = null)
    {
        $base = $this->getBasePath(self::PRODUCT_DIR);

        if (!\File::exists($base)) {
            \File::makeDirectory($base, 0777, true, true);
        }

        return join_paths($base, $path);
    }

    public function getAttachmentsPath($path = null)
    {
        $base = $this->getBasePath(self::ATTACHMENTS_DIR);

        if (!\File::exists($base)) {
            \File::makeDirectory($base, 0777, true, true);
        }

        return join_paths($base, $path);
    }

    public function getLogPath($path = null)
    {
        $base = $this->getBasePath(self::LOGS_DIR);

        if (!\File::exists($base)) {
            \File::makeDirectory($base, 0777, true, true);
        }

        return join_paths($base, $path);
    }

    /**
     * Get active subscription.
     */
    public function activeSubscription()
    {
        return (is_object($this->subscription) && $this->subscription->isActive()) ? $this->subscription : null;
    }

    public function useOwnSendingServer()
    {
        $subscription = $this->activeSubscription();

        if (is_null($subscription)) {
            return false;
        }

        return $subscription->plan->useOwnSendingServer();
    }

    /**
     * Get all items.
     *
     * @return collect
     */
    public static function getAll()
    {
        return self::select('customers.*');
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
    public static function scopeFilter($query, $request)
    {
        // filters
        $filters = $request->all();
        if (!empty($filters)) {
        }

        // Admin filter
        if (!empty($request->admin_id)) {
            $query = $query->where('customers.admin_id', '=', $request->admin_id);
        }
    }

    /**
     * Search items.
     *
     * @return collect
     */
    public static function scopeSearch($query, $keyword)
    {
        $query = $query->select('customers.*')
            ->leftJoin('users', 'users.customer_id', '=', 'customers.id');

        // Keyword
        if (!empty(trim($keyword))) {
            foreach (explode(' ', trim($keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('users.first_name', 'like', '%'.$keyword.'%')
                        ->orWhere('users.last_name', 'like', '%'.$keyword.'%')
                        ->orWhere('users.email', 'like', '%'.$keyword.'%');
                });
            }
        }
    }

    /**
     * Subscribers count by time.
     *
     * @return number
     */
    public static function subscribersCountByTime($begin, $end, $customer_id = null, $list_id = null, $status=null)
    {
        $query = \Acelle\Model\Subscriber::leftJoin('mail_lists', 'mail_lists.id', '=', 'subscribers.mail_list_id')
                                ->leftJoin('customers', 'customers.id', '=', 'mail_lists.customer_id');

        if (isset($list_id)) {
            $query = $query->where('subscribers.mail_list_id', '=', $list_id);
        }
        if (isset($customer_id)) {
            $query = $query->where('customers.id', '=', $customer_id);
        }
        if (isset($status)) {
            $query = $query->where('subscribers.status', '=', $status);
        }

        $query = $query->where('subscribers.created_at', '>=', $begin)
                        ->where('subscribers.created_at', '<=', $end);

        return $query->count();
    }

    /**
     * Get max list quota.
     *
     * @return number
     */
    public function maxLists()
    {
        $count = $this->getOption('list_max');
        if ($count == -1) {
            return '∞';
        } else {
            return $count;
        }
    }

    /**
     * Count customer lists.
     *
     * @return number
     */
    public function listsCount()
    {
        return $this->lists()->count();
    }

    /**
     * Calculate list usage.
     *
     * @return number
     */
    public function listsUsage()
    {
        $max = $this->maxLists();
        $count = $this->listsCount();

        if ($max == '∞') {
            return 0;
        }

        if ($max == 0) {
            return 0;
        }

        if ($count > $max) {
            return 100;
        }

        return round((($count / $max) * 100), 2);
    }

    /**
     * Display calculate list usage.
     *
     * @return number
     */
    public function displayListsUsage()
    {
        if ($this->maxLists() == '∞') {
            return trans('messages.unlimited');
        }

        return $this->listsUsage().'%';
    }

    /**
     * Get campaigns quota.
     *
     * @return number
     */
    public function maxCampaigns()
    {
        $count = $this->getOption('campaign_max');
        if ($count == -1) {
            return '∞';
        } else {
            return $count;
        }
    }

    /**
     * Count customer's campaigns.
     *
     * @return number
     */
    public function campaignsCount()
    {
        return $this->campaigns()->count();
    }

    /**
     * Calculate campaign usage.
     *
     * @return number
     */
    public function campaignsUsage()
    {
        $max = $this->maxCampaigns();
        $count = $this->campaignsCount();

        if ($max == '∞') {
            return 0;
        }
        if ($max == 0) {
            return 0;
        }
        if ($count > $max) {
            return 100;
        }

        return round((($count / $max) * 100), 2);
    }

    /**
     * Calculate campaign usage.
     *
     * @return number
     */
    public function displayCampaignsUsage()
    {
        if ($this->maxCampaigns() == '∞') {
            return trans('messages.unlimited');
        }

        return $this->campaignsUsage().'%';
    }

    /**
     * Get subscriber quota.
     *
     * @return number
     */
    public function maxSubscribers()
    {
        $count = $this->getOption('subscriber_max');
        if ($count == -1) {
            return '∞';
        } else {
            return $count;
        }
    }

    /**
     * Count customer's subscribers.
     *
     * @return number
     */
    public function subscribersCount($cache = false)
    {
        if ($cache) {
            return $this->readCache('SubscriberCount');
        }

        // return distinctCount($this->subscribers(), 'subscribers.email', 'distinct');
        return $this->subscribers()->count();
    }

    /**
     * Calculate subscibers usage.
     *
     * @return number
     */
    public function subscribersUsage($cache = false)
    {
        $max = $this->maxSubscribers();
        $count = $this->subscribersCount($cache);

        if ($max == '∞') {
            return 0;
        }
        if ($max == 0) {
            return 0;
        }
        if ($count > $max) {
            return 100;
        }

        return round((($count / $max) * 100), 2);
    }

    /**
     * Calculate subscibers usage.
     *
     * @return number
     */
    public function displaySubscribersUsage()
    {
        if ($this->maxSubscribers() == '∞') {
            return trans('messages.unlimited');
        }

        // @todo: avoid using cached value in a function
        //        cache value must be called directly from view only
        return $this->readCache('SubscriberUsage', 0).'%';
    }

    /**
     * Get customer's quota.
     *
     * @return string
     */
    public function maxQuota()
    {
        $quota = $this->getOption('sending_quota');
        if ($quota == '-1') {
            return '∞';
        } else {
            return $quota;
        }
    }

    /**
     * Check if customer has access to ALL sending servers.
     *
     * @return bool
     */
    public function allSendingServer()
    {
        $check = $this->getOption('all_sending_servers');

        return $check == 'yes';
    }

    /**
     * Get customer's color scheme.
     *
     * @return string
     */
    public function getColorScheme()
    {
        if (!empty($this->color_scheme)) {
            return $this->color_scheme;
        } else {
            return \Acelle\Model\Setting::get('frontend_scheme');
        }
    }

    /**
     * Color array.
     *
     * @return array
     */
    public static function colors($default)
    {
        return [
            ['value' => 'default', 'text' => trans('messages.system_default')],
            ['value' => 'blue', 'text' => trans('messages.blue')],
            ['value' => 'green', 'text' => trans('messages.green')],
            ['value' => 'brown', 'text' => trans('messages.brown')],
            ['value' => 'pink', 'text' => trans('messages.pink')],
            ['value' => 'grey', 'text' => trans('messages.grey')],
            ['value' => 'white', 'text' => trans('messages.white')],
        ];
    }

    /**
     * Disable customer.
     *
     * @return bool
     */
    public function disable()
    {
        $this->status = 'inactive';

        return $this->save();
    }

    /**
     * Enable customer.
     *
     * @return bool
     */
    public function enable()
    {
        $this->status = 'active';

        return $this->save();
    }

    /**
     * Get customer timezone.
     *
     * @return string
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * Get customer language code.
     *
     * @return string
     */
    public function getLanguageCode()
    {
        return is_object($this->language) ? $this->language->code : null;
    }

    /**
     * Get customer language code.
     *
     * @return string
     */
    public function getLanguageCodeFull()
    {
        $region_code = $this->language->region_code ? strtoupper($this->language->region_code) : strtoupper($this->language->code);
        return is_object($this->language) ? ($this->language->code.'-'.$region_code) : null;
    }

    /**
     * Get customer select2 select options.
     *
     * @return array
     */
    public static function select2($request)
    {
        $data = ['items' => [], 'more' => true];

        $query = \Acelle\Model\Customer::getAll()->leftJoin('users', 'users.customer_id', '=', 'customers.id');
        if (isset($request->q)) {
            $keyword = $request->q;
            $query = $query->where(function ($q) use ($keyword) {
                $q->orwhere('users.first_name', 'like', '%'.$keyword.'%')
                    ->orWhere('users.last_name', 'like', '%'.$keyword.'%')
                    ->orWhere('users.email', 'like', '%'.$keyword.'%');
            });
        }

        // Read all check
        if (!$request->user()->admin->can('readAll', new \Acelle\Model\Customer())) {
            $query = $query->where('customers.admin_id', '=', $request->user()->admin->id);
        }

        foreach ($query->limit(20)->get() as $customer) {
            $data['items'][] = ['id' => $customer->uid, 'text' => $customer->displayNameEmailOption()];
        }

        return json_encode($data);
    }

    /**
     * Create/Update customer information.
     *
     * @return object
     */
    public function createAccountAndUser($request)
    {
        $user = new User();

        DB::transaction(function () use ($request, &$user) {
            // Customer
            $this->fill($request->all());
            $this->status = self::STATUS_ACTIVE;
            $this->save();

            // User
            $user = new User();
            $user->fill($request->all());
            $user->password = bcrypt($request->password);
            $user->customer()->associate($this);
            $user->save();
        });

        // Important: return the newly created USER
        return $user;
    }

    public function sendingServers()
    {
        return $this->hasMany('Acelle\Model\SendingServer');
    }

    public function subAccounts()
    {
        return $this->hasMany('Acelle\Model\SubAccount');
    }

    /**
     * Customers count by time.
     *
     * @return number
     */
    public static function customersCountByTime($begin, $end, $admin = null)
    {
        $query = \Acelle\Model\Customer::select('customers.*');

        if (isset($admin) && !$admin->can('readAll', new \Acelle\Model\Customer())) {
            $query = $query->where('customers.admin_id', '=', $admin->id);
        }

        $query = $query->where('customers.created_at', '>=', $begin)
            ->where('customers.created_at', '<=', $end);

        return $query->count();
    }

    public function getSubscriberCountByStatus($status)
    {
        // @note: in this particular case, a simple count(distinct) query is much more efficient
        $query = $this->subscribers()->where('subscribers.status', $status)->distinct('subscribers.email');

        return $query->count();
    }

    /**
     * Update Campaign cached data.
     */
    public function updateCache($key = null)
    {
        // cache indexes
        $index = [
            // @note: SubscriberCount must come first as its value shall be used by the others
            'SubscriberCount' => function (&$customer) {
                return $customer->subscribersCount(false);
            },
            'SubscriberUsage' => function (&$customer) {
                return $customer->subscribersUsage(true);
            },
            'SubscribedCount' => function (&$customer) {
                return $customer->getSubscriberCountByStatus(Subscriber::STATUS_SUBSCRIBED);
            },
            'UnsubscribedCount' => function (&$customer) {
                return $customer->getSubscriberCountByStatus(Subscriber::STATUS_UNSUBSCRIBED);
            },
            'UnconfirmedCount' => function (&$customer) {
                return $customer->getSubscriberCountByStatus(Subscriber::STATUS_UNCONFIRMED);
            },
            'BlacklistedCount' => function (&$customer) {
                return $customer->getSubscriberCountByStatus(Subscriber::STATUS_BLACKLISTED);
            },
            'SpamReportedCount' => function (&$customer) {
                return $customer->getSubscriberCountByStatus(Subscriber::STATUS_SPAM_REPORTED);
            },
            'MailListSelectOptions' => function (&$customer) {
                return $customer->getMailListSelectOptions([], true);
            },
            'Bounce/FeedbackRate' => function (&$customer) {
                return $customer->getBounceFeedbackRate();
            },

        ];

        // retrieve cached data
        $cache = json_decode($this->cache, true);
        if (empty($cache)) {
            $cache = [];
        }

        if (is_null($key)) {
            // update all cache
            foreach ($index as $key => $callback) {
                $cache[$key] = $callback($this);
                if ($key == 'SubscriberCount') {
                    // SubscriberCount cache must always be updated as its value will be used for the others
                    $this->cache = json_encode($cache);
                    $this->save();
                }
            }
        } else {
            // update specific key
            $callback = $index[$key];
            $cache[$key] = $callback($this);
        }

        // write back to the DB
        $this->cache = json_encode($cache);
        $this->save();
    }

    /**
     * Retrieve Campaign cached data.
     *
     * @return mixed
     */
    public function readCache($key, $default = null)
    {
        $cache = json_decode($this->cache, true);
        if (is_null($cache)) {
            return $default;
        }
        if (array_key_exists($key, $cache)) {
            if (is_null($cache[$key])) {
                return $default;
            } else {
                return $cache[$key];
            }
        } else {
            return $default;
        }
    }

    public function getBounceFeedbackRate()
    {
        $delivery = $this->trackingLogs()->count();

        if ($delivery == 0) {
            return 0;
        }

        $bounce = DB::table('bounce_logs')->leftJoin('tracking_logs', 'tracking_logs.message_id', '=', 'bounce_logs.message_id')->count();
        $feedback = DB::table('feedback_logs')->leftJoin('tracking_logs', 'tracking_logs.message_id', '=', 'feedback_logs.message_id')->count();

        $percentage = ($feedback + $bounce) / $delivery;
    }

    /**
     * Sending servers count.
     *
     * @var int
     */
    public function sendingServersCount()
    {
        return $this->sendingServers()->count();
    }

    /**
     * Sending domains count.
     *
     * @var int
     */
    public function sendingDomainsCount()
    {
        return $this->sendingDomains()->count();
    }

    /**
     * Get max sending server count.
     *
     * @var int
     */
    public function maxSendingServers()
    {
        $count = $this->getOption('sending_servers_max');
        if ($count == -1) {
            return '∞';
        } else {
            return $count;
        }
    }

    /**
     * Get max email verification server count.
     *
     * @var int
     */
    public function maxEmailVerificationServers()
    {
        $count = $this->getOption('email_verification_servers_max');
        if ($count == -1) {
            return '∞';
        } else {
            return $count;
        }
    }

    /**
     * Calculate email verification server usage.
     *
     * @return number
     */
    public function emailVerificationServersUsage()
    {
        $max = $this->maxEmailVerificationServers();
        $count = $this->emailVerificationServersCount();

        if ($max == '∞') {
            return 0;
        }
        if ($max == 0) {
            return 0;
        }
        if ($count > $max) {
            return 100;
        }

        return round((($count / $max) * 100), 2);
    }

    /**
     * Calculate email verigfication servers usage.
     *
     * @return number
     */
    public function displayEmailVerificationServersUsage()
    {
        if ($this->maxEmailVerificationServers() == '∞') {
            return trans('messages.unlimited');
        }

        return $this->emailVerificationServersUsage().'%';
    }

    /**
     * Calculate sending servers usage.
     *
     * @return number
     */
    public function sendingServersUsage()
    {
        $max = $this->maxSendingServers();
        $count = $this->sendingServersCount();

        if ($max == '∞') {
            return 0;
        }
        if ($max == 0) {
            return 0;
        }
        if ($count > $max) {
            return 100;
        }

        return round((($count / $max) * 100), 2);
    }

    /**
     * Calculate sending servers usage.
     *
     * @return number
     */
    public function displaySendingServersUsage()
    {
        if ($this->maxSendingServers() == '∞') {
            return trans('messages.unlimited');
        }

        return $this->sendingServersUsage().'%';
    }

    /**
     * Get max sending server count.
     *
     * @var int
     */
    public function maxSendingDomains()
    {
        $count = $this->getOption('sending_domains_max');
        if ($count == -1) {
            return '∞';
        } else {
            return $count;
        }
    }

    /**
     * Calculate subscibers usage.
     *
     * @return number
     */
    public function sendingDomainsUsage()
    {
        $max = $this->maxSendingDomains();
        $count = $this->sendingDomainsCount();

        if ($max == '∞') {
            return 0;
        }
        if ($max == 0) {
            return 0;
        }
        if ($count > $max) {
            return 100;
        }

        return round((($count / $max) * 100), 2);
    }

    /**
     * Calculate subscibers usage.
     *
     * @return number
     */
    public function displaySendingDomainsUsage()
    {
        if ($this->maxSendingDomains() == '∞') {
            return trans('messages.unlimited');
        }

        return $this->sendingDomainsUsage().'%';
    }

    /**
     * Count customer automations.
     *
     * @return number
     */
    public function automationsCount()
    {
        return $this->automation2sCount();
    }

    /**
     * Get max automation count.
     *
     * @var int
     */
    public function maxAutomations()
    {
        $count = $this->getOption('automation_max');
        if ($count == -1) {
            return '∞';
        } else {
            return $count;
        }
    }

    /**
     * Calculate subscibers usage.
     *
     * @return number
     */
    public function automationsUsage()
    {
        $max = $this->maxAutomations();
        $count = $this->automation2sCount();

        if ($max == '∞') {
            return 0;
        }
        if ($max == 0) {
            return 0;
        }
        if ($count > $max) {
            return 100;
        }

        return round((($count / $max) * 100), 2);
    }

    /**
     * Calculate subscibers usage.
     *
     * @return number
     */
    public function displayAutomationsUsage()
    {
        if ($this->maxAutomations() == '∞') {
            return trans('messages.unlimited');
        }

        return $this->automationsUsage().'%';
    }

    /**
     * Check if customer has admin account.
     *
     * @return bool
     */
    public function hasAdminAccount()
    {
        return is_object($this->user) && is_object($this->user->admin);
    }

    /**
     * Get all customer active sending servers.
     *
     * @return collect
     */
    public function activeSendingServers()
    {
        return $this->sendingServers()->where('status', '=', \Acelle\Model\SendingServer::STATUS_ACTIVE);
    }

    /**
     * Check if customer is disabled.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    /**
     * Check if customer is disabled.
     *
     * @return bool
     */
    public function currentPlanName()
    {
        return is_object($this->activeSubscription()) ?
            $this->activeSubscription()->plan->name :
            ''
        ;
    }

    /**
     * Get total file size usage.
     *
     * @return number
     */
    public function totalUploadSize()
    {
        return \Acelle\Library\Tool::getDirectorySize(base_path('public/source/'.$this->user->uid)) / 1048576;
    }

    /**
     * Get max upload size quota.
     *
     * @return number
     */
    public function maxTotalUploadSize()
    {
        $count = $this->getOption('max_size_upload_total');
        if ($count == -1) {
            return '∞';
        } else {
            return $count;
        }
    }

    /**
     * Calculate campaign usage.
     *
     * @return number
     */
    public function totalUploadSizeUsage()
    {
        if ($this->maxTotalUploadSize() == '∞') {
            return 0;
        }
        if ($this->maxTotalUploadSize() == 0) {
            return 100;
        }

        return round((($this->totalUploadSize() / $this->maxTotalUploadSize()) * 100), 2);
    }

    /**
     * Custom can for customer.
     *
     * @return bool
     */
    public function can($action, $item)
    {
        return $this->user->can($action, [$item, 'customer']);
    }

    /**
     * Get customer contact.
     *
     * @return Contact
     */
    public function getContact()
    {
        if (is_object($this->contact)) {
            $contact = $this->contact;
        } else {
            $contact = new \Acelle\Model\Contact([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->user->email,
            ]);
        }

        return $contact;
    }

    /*
     * Custom name + email.
     *
     * @return string
     */
    public function displayNameEmailOption()
    {
        return $this->user->displayName().'|||'.$this->user->email;
    }

    /**
     * Email verification servers count.
     *
     * @var int
     */
    public function emailVerificationServersCount()
    {
        return $this->emailVerificationServers()->count();
    }

    /**
     * Get list of available email verification servers.
     *
     * @var bool
     */
    public function getEmailVerificationServers()
    {
        // Check the customer has permissions using email verification servers and has his own email verification servers
        if ($this->getOption('create_email_verification_servers') == 'yes') {
            return $this->activeEmailVerificationServers()->get()->map(function ($server) {
                return $server;
            });
        // If customer dont have permission creating sending servers
        } else {
            // Get server from the plan
            return  $this->activeSubscription()->plan->getEmailVerificationServers();
        }
    }

    /**
     * Get the list of available mail lists, used for populating select box.
     *
     * @return array
     */
    public function getMailListSelectOptions($options = [], $cache = false)
    {
        $query = $this->mailLists();

        # Other list
        if (isset($options['other_list_of'])) {
            $query->where('id', '!=', $options['other_list_of']);
        }

        $result = $query->orderBy('name')->get()->map(function ($item) use ($cache) {
            return ['id' => $item->id, 'value' => $item->uid, 'text' => $item->name.' ('.$item->subscribersCount($cache).' '.strtolower(trans('messages.subscribers')).')'];
        });

        return $result;
    }

    /**
     * Get email verification servers select options.
     *
     * @return array
     */
    public function emailVerificationServerSelectOptions()
    {
        $servers = $this->getEmailVerificationServers();
        $options = [];
        foreach ($servers as $server) {
            $options[] = ['text' => $server->name, 'value' => $server->uid];
        }

        return $options;
    }

    /**
     * Get customer's sending servers type.
     *
     * @return array
     */
    public function getSendingServertypes()
    {
        $allTypes = \Acelle\Model\SendingServer::types();
        $types = [];

        foreach ($allTypes as $type => $server) {
            if ($this->isAllowCreateSendingServerType($type)) {
                $types[$type] = $server;
            }
        }

        if (!Setting::isYes('delivery.sendmail')) {
            unset($types['sendmail']);
        }

        if (!Setting::isYes('delivery.phpmail')) {
            unset($types['php-mail']);
        }

        return $types;
    }

    /**
     * Check customer can create sending servers type.
     *
     * @return bool
     */
    public function isAllowCreateSendingServerType($type)
    {
        $customerTypes = $this->getOption('sending_server_types');
        if ($this->getOption('all_sending_server_types') == 'yes' ||
            (isset($customerTypes[$type]) && $customerTypes[$type] == 'yes')
        ) {
            return true;
        }

        return false;
    }

    /**
     * Add email to customer's blacklist.
     */
    public function addEmaillToBlacklist($email)
    {
        $email = trim(strtolower($email));

        if (\Acelle\Library\Tool::isValidEmail($email)) {
            $exist = $this->blacklists()->where('email', '=', $email)->count();
            if (!$exist) {
                $blacklist = new \Acelle\Model\Blacklist();
                $blacklist->customer_id = $this->id;
                $blacklist->email = $email;
                $blacklist->save();
            }
        }
    }

    /**
     * Get customer options.
     *
     * @return string
     */
    public function getOptions()
    {
        if (is_object($this->activeSubscription())) {
            // Find plan
            return $this->activeSubscription()->plan->getOptions();
        } else {
            return [];
        }
    }

    /**
     * Get customer option.
     *
     * @return string
     */
    public function getOption($name)
    {
        $options = $this->getOptions();
        // @todo: chỗ này cần raise 1 cái exception chứ ko phải là trả về rỗng (trả vể rỗng để "dìm" lỗi đi à?)
        return isset($options[$name]) ? $options[$name] : null;
    }

    /**
     * Check if customer has api access.
     *
     * @return bool
     */
    public function canUseApi()
    {
        return $this->getOption('api_access') == 'yes';
    }

    /**
     * Get all verified identities.
     *
     * @return array
     */
    public function getVerifiedIdentities()
    {
        $list = [];

        // own emails
        if ($this->allowSenderVerification()) {
            $senders = $this->senders()->verified()->get();
            foreach ($senders as $sender) {
                $list[] = $sender->name.' <'.$sender->email.'>';
            }
        }

        // own domain
        $domains = $this->sendingDomains()->active();
        $plan = $this->activeSubscription()->plan;
        if ($plan->useSystemSendingServer()) {
            $server = $plan->primarySendingServer();
            if (!$server->allowOtherSendingDomains()) {
                $domains->bySendingServer($server);
            }
        }

        foreach ($domains->get() as $domain) {
            $list[] = $domain->name;
        }

        // plan sending server emails if system sending servers
        if ($plan->useSystemSendingServer()) {
            $list = array_merge($plan->getVerifiedIdentities(), $list);
        }

        return array_values(array_unique($list));
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
                $eKey = $dKey[0];
                $dKey = isset($dKey[1]) ? $dKey[1] : null;
                // if ( (!isset($dKey) || $dKey == '') || ($dKey && strpos(strtolower($item), $dKey) === 0 )) {
                if ($keyword == '###') {
                    $eKey = '****';
                }
                $topList[] = [
                        'text' => $eKey.'@'.str_replace($dKey, '<span class="text-semibold text-primary"><strong>'.$dKey.'</strong></span>', $item),
                        'subfix' => $item,
                        'desc' => null,
                    ];
                // }
            }
        }

        $droplist = array_merge($topList, $bottomList);

        return $droplist;
    }


    public function getCurrentSubscription()
    {
        return $this->subscription;
    }

    /**
     * Count customer automation2s.
     *
     * @return number
     */
    public function automation2sCount()
    {
        return $this->automation2s()->count();
    }

    /**
     * Assign plan to customer.
     */
    public function assignPlan($plan)
    {
        $subscription = $this->subscription;
        // create tmp subscription
        if (!$subscription) {
            $subscription = new Subscription();
            $subscription->customer_id = $this->id;
        }

        // cancel all pending invoices (new)
        $subscription->invoices()->new()->delete();

        // reset value
        $subscription->plan_id = $plan->id;
        $subscription->current_period_ends_at = null;
        $subscription->ends_at = null;
        $subscription->started_at = null;
        $subscription->status = Subscription::STATUS_NEW;

        // Important:
        // With current quota tracking design, there is no fixed 'available credits'
        // As a result, the setCredits() method takes QUOTA_UNLIMITED value as default, meaning no credits check
        // The application only enforce the quota limits rules: how many credits are allowed during a fixed period of time (month)
        // It is pre-designed for "Buy Credits" feature which will be supported later on
        $subscription->setCredits('send', QuotaManager::QUOTA_UNLIMITED);

        // Actually store
        $subscription->save();

        // create init invoice
        $subscription->createInitInvoice();

        return $subscription;
    }

    /**
     * Check customer if has notice.
     */
    public function hasSubscriptionNotice()
    {
        $subscription = $this->subscription;

        if (!$subscription) {
            return false;
        }

        if ($subscription->getUnpaidInvoice()) {
            return true;
        }

        return false;
    }

    public function isSubscriptionActive()
    {
        $subscription = $this->activeSubscription();
        if (is_null($subscription)) {
            return false;
        }

        return $subscription->isActive();
    }

    public function getLockPath($path)
    {
        return $this->user->getLockPath($path);
    }

    public function allowUnverifiedFromEmailAddress()
    {
        $plan = $this->subscription->plan;

        if (!$plan) {
            return false;
        }

        if ($plan->useSystemSendingServer() && is_object($plan->primarySendingServer())) {
            return $plan->primarySendingServer()->allowUnverifiedFromEmailAddress();
        }

        return true;
    }

    /**
     * Get current period ends at.
     */
    public function getCurrentPeriodEndsAt()
    {
        return (is_object($this->subscription) && $this->subscription->isActive()) ? $this->current_period_ends_at : null;
    }

    /**
     * Get list of available tracking domain options.
     *
     * @var bool
     */
    public function getVerifiedTrackingDomainOptions()
    {
        return $this->trackingDomains()->verified()->get()->map(function ($domain) {
            return ['value' => $domain->uid, 'text' => $domain->name];
        });
    }

    // Get the current time in Customer timezone
    public function getCurrentTime()
    {
        return Carbon::now($this->timezone);
    }

    public function parseDateTime($datetime, $fallback = false)
    {
        // IMPORTANT: datetime string must NOT contain timezone information
        try {
            $dt = Carbon::parse($datetime, $this->timezone);
            $dt = $dt->timezone($this->timezone);
        } catch (\Exception $ex) {
            // parse special chars, for example ('sadfh&#($783943') ==> exception
            if ($fallback) {
                $dt = $this->parseDateTime('1900-01-01');
            } else {
                throw $ex;
            }
        }
        return $dt;
    }

    public function formatCurrentDateTime($name)
    {
        return $this->formatDateTime($this->getCurrentTime(), $name);
    }

    public function formatDateTime(Carbon $datetime, string $name)
    {
        // $name is a format name like: date_full | date_short
        // See config('localization')['*'] for the full list of available format names

        return format_datetime($datetime, $name, $this->getLanguageCode());
    }

    /**
     * Get customer product source.
     *
     * @var collect
     */
    public function newProductSource($type)
    {
        $class = '\\Acelle\\Model\\' . $type;
        $source = new $class();
        $source->customer_id = $this->id;
        $source->type = $type;

        return $source;
    }

    /**
     * Get customer product source.
     *
     * @var collect
     */
    public function findProductSource($type)
    {
        $source = $this->sources()
            ->where('type', '=', $type)
            ->first();

        if (!$source) {
            $source = new Source();
            $source->customer_id = $this->id;
            $source->type = $type;
        }

        return $source;
    }

    /**
     * Get source select options.
     *
     * @return object
     */
    public function getSelectOptions($type=null)
    {
        $query = $this->sources();

        if ($type) {
            $query = $query->where('type', '=', $type);
        }

        return $query->get()->map(function ($source) {
            return ['text' => $source->getData()['data']['name'], 'value' => $source->uid];
        });
    }

    /**
     * Get payment method.
     *
     * @var object | collect
     */
    public function getPreferredPaymentGateway()
    {
        if (!$this['payment_method']) {
            return null;
        }

        // For example, the user preferred method is "paddle", but paddle is no longer available
        // Then simply return null
        // So, in the web UI, this user does not have a preferred payment method
        $meta = json_decode($this['payment_method'], true);

        if (!array_key_exists('method', $meta)) {
            throw new Exception("The 'method' key is required for 'preferred payment' data");
        }

        $type = $meta['method'];

        if ($type && Billing::isGatewayTypeRegistered($type)) {
            return Billing::getGateway($type);
        } else {
            // payment method was previously stored but it is dropped in the current version
            return null;
        }
    }

    /**
     * Update payment method.
     *
     * @var object | collect
     */
    public function updatePaymentMethod($data=[])
    {
        // $paymentMethod = $this->getPaymentMethod();

        // if (!isset($paymentMethod)) {
        //     $paymentMethod = [];
        // }

        // $data = (object) array_merge((array) $paymentMethod, $data);
        $this['payment_method'] = json_encode($data);

        $this->save();
    }

    /**
     * Update payment method.
     *
     * @var object | collect
     */
    public function updatePaymentMethodData($data=[])
    {
        $paymentMethod = $this->getPaymentMethod();

        if (!isset($paymentMethod)) {
            $paymentMethod = [];
        }

        $data = (object) array_merge((array) $paymentMethod, $data);
        $this['payment_method'] = json_encode($data);

        $this->save();
    }

    /**
     * Remove payment method.
     *
     * @var object | collect
     */
    public function removePaymentMethod()
    {
        $this->payment_method = null;
        $this->save();
    }

    /**
     * Get default billing address.
     *
     * @var object
     */
    public function newBillingAddress()
    {
        $address = new \Acelle\Model\BillingAddress();
        $address->customer_id = $this->id;
        return $address;
    }

    /**
     * Get default billing address.
     *
     * @var object
     */
    public function getDefaultBillingAddress()
    {
        return $this->billingAddresses()->first();
    }

    public function createAbandonedEmailAutomation($store)
    {
        $auto = $this->automation2s()->create([
            'name' => 'Abandoned Cart Notification - Auto',
            'mail_list_id' => $store->getList()->id,
            'status' => 'inactive',
        ]);

        $email = new \Acelle\Model\Email([
            'action_id' => '1000000001',
        ]);
        $email->customer_id = $this->id;
        $email->automation2_id = $auto->id;
        $email->save();

        $auto->data = json_encode([
            [
                "title" => "Abandoned Cart Reminder",
                "id" => "trigger",
                "type" => "ElementTrigger",
                "child" => "1000000001",
                "options" => [
                    "key" => "woo-abandoned-cart",
                    "type" => "woo-abandoned-cart",
                    "source_uid" => $store->uid,
                    "wait" => "24_hour",
                    "init"  => "true"
                ]
            ], [
                "title" => "Hey, you have an item left in cart",
                "id" => "1000000001",
                "type" => "ElementAction",
                "child" => null,
                "options" => [
                    "init" => "true",
                    "email_uid" => $email->uid
                ]
            ]
        ]);

        $auto->save();

        return $auto;
    }

    public function getAbandonedEmailAutomation($store)
    {
        $auto = $this->automation2s()->where('mail_list_id', '=', $store->mail_list_id)->first();
        if (!$auto) {
            $auto = $this->createAbandonedEmailAutomation($store);
        }
        return $auto;
    }

    /**
     * Get auto billing data.
     *
     * @var object
     */
    public function getAutoBillingData()
    {
        if ($this->auto_billing_data == null) {
            return null;
        }

        return AutoBillingData::fromJson($this->auto_billing_data);
    }

    /**
     * Get auto billing data.
     *
     * @var object
     */
    public function setAutoBillingData(AutoBillingData $autoBillingData)
    {
        $this->auto_billing_data = $autoBillingData->toJson();
        $this->save();
    }

    // Do not call delete() directly on Customer
    // This method helps delete Customer account but KEEP
    // the associated User account if it is also associated with an Admin
    public function deleteAccount()
    {
        DB::transaction(function () {
            $user = $this->user;
            $this->delete();
            if (!$user->admin()->exists()) {
                $user->deleteAndCleanup();
            }
        });
    }

    public function copyTemplateAs(Template $template, $name)
    {
        $copy = $template->copy([
            'name' => $name,
            'customer_id' => $this->id
        ]);

        return $copy;
    }

    /**
     * Get builder templates.
     *
     * @return mixed
     */
    public function getBuilderTemplates()
    {
        $result = [];

        // Gallery
        $templates = \Acelle\Model\Template::where('customer_id', '=', null)
            ->orWhere('customer_id', '=', $this->id)
            ->orderBy('customer_id')
            ->get();

        foreach ($templates as $template) {
            $result[] = [
                'name' => $template->name,
                'url' => '', // action('CampaignController@templateChangeTemplate', ['uid' => $this->uid, 'template_uid' => $template->uid]),
                'thumbnail' => $template->getThumbUrl(),
            ];
        }

        return $result;
    }

    public function preferredPaymentGatewayCanAutoCharge()
    {
        // check auto billing data
        if ($this->getPreferredPaymentGateway() == null) {
            return false;
        }

        // support auto billing
        if (!$this->getPreferredPaymentGateway()->supportsAutoBilling()) {
            return false;
        }

        // no autobilling data
        if ($this->getAutoBillingData() == null || $this->getAutoBillingData()->getGateway() == null) {
            return false;
        }

        // check payment
        if ($this->getPreferredPaymentGateway()->getType() != $this->getAutoBillingData()->getGateway()->getType()) {
            return false;
        }

        return true;
    }

    public function importBlacklistJobs()
    {
        return $this->jobMonitors()->orderBy('job_monitors.id', 'DESC')->where('job_type', ImportBlacklistJob::class);
    }

    public function getMenuLayout()
    {
        return ($this->menu_layout == 'left' ? 'left' : 'top');
    }

    public function allowSenderVerification()
    {
        $subscription = $this->activeSubscription();

        if (is_null($subscription)) {
            return false;
        }

        $plan = $subscription->plan;
        if ($plan->useSystemSendingServer()) {
            $server = $plan->primarySendingServer();
            if ($server && ($server->allowVerifyingOwnEmailsRemotely() || $server->allowVerifyingOwnDomains())) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public function getConnectedWebsiteSelectOptions($long=true)
    {
        $query = $this->websites()->connected();

        $result = $query->orderBy('title')->get()->map(function ($item) use ($long) {
            if ($long) {
                return ['value' => $item->uid, 'text' => '<span class="fw-600">' . $item->title . '</span><br><span class="text-muted">' . $item->url . '</span>'];
            } else {
                return ['value' => $item->uid, 'text' => $item->title];
            }
        });

        return $result;
    }

    public function updateBillingInformationFromInvoice($invoice)
    {
        $billingAddress = $this->getDefaultBillingAddress();

        // has no address yet
        if (!$billingAddress) {
            $billingAddress = $this->newBillingAddress();
        }

        $billingAddress->fill([
            'first_name' => $invoice->billing_first_name,
            'last_name' => $invoice->billing_last_name,
            'address' => $invoice->billing_address,
            'email' => $invoice->billing_email,
            'phone' => $invoice->billing_phone,
            'country_id' => $invoice->billing_country_id,
        ]);

        $billingAddress->save();
    }

    public static function newCustomer()
    {
        $customer = new self();
        $customer->menu_layout = \Acelle\Model\Setting::get('layout.menu_bar');

        return $customer;
    }

    public function newProduct()
    {
        $product = new \Acelle\Model\Product();
        $product->customer_id = $this->id;

        return $product;
    }

    public function isCustomTrackingDomainRequired()
    {
        $plan = $this->subscription->plan;

        if (!$plan) {
            return false;
        }

        return $plan->isCustomTrackingDomainRequired();
    }
}
