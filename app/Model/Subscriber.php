<?php

/**
 * Subscriber class.
 *
 * Model class for Subscriber
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
use Acelle\Model\MailList;
use Acelle\Model\Setting;
use Acelle\Events\MailListSubscription;
use Acelle\Events\MailListUnsubscription;
use Acelle\Library\StringHelper;
use DB;
use Exception;
use File;
use Acelle\Library\Traits\HasUid;
use Closure;
use Carbon\Carbon;

class Subscriber extends Model
{
    use HasUid;

    public const STATUS_SUBSCRIBED = 'subscribed';
    public const STATUS_UNSUBSCRIBED = 'unsubscribed';
    public const STATUS_BLACKLISTED = 'blacklisted';
    public const STATUS_SPAM_REPORTED = 'spam-reported';
    public const STATUS_UNCONFIRMED = 'unconfirmed';

    public const SUBSCRIPTION_TYPE_ADDED = 'added';
    public const SUBSCRIPTION_TYPE_DOUBLE_OPTIN = 'double';
    public const SUBSCRIPTION_TYPE_SINGLE_OPTIN = 'single';
    public const SUBSCRIPTION_TYPE_IMPORTED = 'imported';

    public const VERIFICATION_STATUS_DELIVERABLE = 'deliverable';
    public const VERIFICATION_STATUS_UNDELIVERABLE = 'undeliverable';
    public const VERIFICATION_STATUS_UNKNOWN = 'unknown';
    public const VERIFICATION_STATUS_RISKY = 'risky';
    public const VERIFICATION_STATUS_UNVERIFIED = 'unverified';

    protected $dates = ['unsubscribed_at'];

    public static $rules = [
        'email' => ['required', 'email:rfc,filter'],
    ];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mail_list_id', 'email',
        'image',
    ];

    /**
     * Associations.
     *
     * @var object | collect
     */
    public function mailList()
    {
        return $this->belongsTo('Acelle\Model\MailList');
    }

    public function subscriberFields()
    {
        return $this->hasMany('Acelle\Model\SubscriberField');
    }

    public function trackingLogs()
    {
        return $this->hasMany('Acelle\Model\TrackingLog');
    }

    public function unsubscribeLogs()
    {
        return $this->hasMany('Acelle\Model\UnsubscribeLog');
    }

    public function scopeUnverified($query)
    {
        return $query->whereNull('subscribers.verification_status');
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('subscribers.verification_status');
    }

    public function scopeDeliverable($query)
    {
        return $query->where('subscribers.verification_status', self::VERIFICATION_STATUS_DELIVERABLE);
    }

    public function scopeDeliverableOrNotVerified($query)
    {
        return $query->whereRaw(sprintf(
            "(%s = '%s' OR %s IS NULL)",
            table('subscribers.verification_status'),
            self::VERIFICATION_STATUS_DELIVERABLE,
            table('subscribers.verification_status')
        ));
    }

    public function scopeUndeliverable($query)
    {
        return $query->where('subscribers.verification_status', self::VERIFICATION_STATUS_UNDELIVERABLE);
    }

    public function scopeUnknown($query)
    {
        return $query->where('subscribers.verification_status', self::VERIFICATION_STATUS_UNKNOWN);
    }

    public function scopeRisky($query)
    {
        return $query->where('subscribers.verification_status', self::VERIFICATION_STATUS_RISKY);
    }

    /**
     * Bootstrap any application services.
     */
    public static function boot()
    {
        parent::boot();

        // Create uid when creating list.
        static::creating(function ($item) {
            $item->uid = uniqid();
        });

        static::updated(function ($item) {
            $item->reformatDateFields();
        });
    }

    /**
     * Get rules.
     *
     * @var array
     */
    public function getRules()
    {
        $rules = $this->mailList->getFieldRules();
        $item_id = isset($this->id) ? $this->id : 'NULL';
        $rules['EMAIL'] = $rules['EMAIL'].'|unique:subscribers,email,'.$item_id.',id,mail_list_id,'.$this->mailList->id;

        return $rules;
    }

    /**
     * Blacklist a subscriber.
     *
     * @return bool
     */
    public function sendToBlacklist($reason = null)
    {
        // blacklist all email
        self::where('email', $this->email)->update(['status' => self::STATUS_BLACKLISTED]);

        // create an entry in blacklists table
        $r = Blacklist::firstOrNew(['email' => $this->email]);
        $r->reason = $reason;
        $r->save();

        return true;
    }

    /**
     * Mark a subscriber/list as abuse-reported.
     *
     * @return bool
     */
    public function markAsSpamReported()
    {
        $this->status = self::STATUS_SPAM_REPORTED;
        $this->save();

        return true;
    }

    /**
     * Unsubscribe to the list.
     */
    public function unsubscribe($trackingInfo)
    {
        // Transaction safe
        DB::transaction(function () use ($trackingInfo) {
            // Update status
            $this->status = self::STATUS_UNSUBSCRIBED;
            $this->save();

            // Trigger events
            MailListUnsubscription::dispatch($this);

            // Create log
            $this->unsubscribeLogs()->create($trackingInfo);
        });
    }

    /**
     * Update fields from request.
     */
    public function updateFields($params)
    {
        foreach ($this->mailList->getFields as $field) {
            // Thank you John Wigley and acorna.com team for pointing this out
            if (!isset($params[$field->tag])) {
                $params[$field->tag] = null;  // Fix for inability to clear checkboxes and multiselects, add in null elements for those missing from the form submission but defined as custom fields for that mailing list
            }
        }

        foreach ($params as $tag => $value) {
            $field = $this->mailList->getFieldByTag(str_replace('[]', '', $tag));
            if (is_object($field)) {
                $fv = SubscriberField::where('subscriber_id', '=', $this->id)->where('field_id', '=', $field->id)->first();
                if (!is_object($fv)) {
                    $fv = new SubscriberField();
                    $fv->subscriber_id = $this->id;
                    $fv->field_id = $field->id;
                }
                if (is_array($value)) {
                    $fv->value = implode(',', $value);
                } else {
                    $fv->value = $value;
                }
                $fv->save();

                // update email attribute of subscriber
                if ($field->tag == 'EMAIL') {
                    $this->email = $fv->value;
                    $this->save();
                }
            }
        }
    }

    public function updateFields2($attributes)
    {
        foreach ($attributes as $tag => $value) {
            $field = $this->mailList->getFieldByTag($tag);
            if (!is_null($field)) {
                $fv = $this->subscriberFields()->where('field_id', '=', $field->id)->first();

                if (is_null($fv)) {
                    $fv = $this->subscriberFields()->make();
                    $fv->field()->associate($field);
                }

                $fv->value = $value;
                $fv->save();

                // @IMPORTANT: avoid updating 'subscribers' table, especially for jobs!
                // update email attribute of subscriber
                // if (strcasecmp($field->tag, 'EMAIL') == 0) {
                //     $this->email = $fv->value;
                //     $this->save();
                // }
            }
        }
    }

    /**
     * Filter items.
     *
     * @return collect
     */
    public static function filter($query, $request)
    {
        /* does not support searching on subscriber fields, for the sake of performance
        $query = $query->leftJoin('subscriber_fields', 'subscribers.id', '=', 'subscriber_fields.subscriber_id')
            ->leftJoin('mail_lists', 'subscribers.mail_list_id', '=', 'mail_lists.id');
        */
        $query = $query->leftJoin('mail_lists', 'subscribers.mail_list_id', '=', 'mail_lists.id');

        if (isset($request)) {
            // Keyword
            if (!empty(trim($request->keyword))) {
                foreach (explode(' ', trim($request->keyword)) as $keyword) {
                    $query = $query->where(function ($q) use ($keyword) {
                        $q->orwhere('subscribers.email', 'like', '%'.$keyword.'%');
                        /* does not support searching on subscriber fields, for the sake of performance
                        ->orWhere('subscriber_fields.value', 'like', '%'.$keyword.'%');
                        */
                    });
                }
            }

            // filters
            $filters = $request->filters;
            if (!empty($filters)) {
                if (!empty($filters['status'])) {
                    $query = $query->where('subscribers.status', '=', $filters['status']);
                }
                if (!empty($filters['verification_result'])) {
                    if ($filters['verification_result'] == 'unverified') {
                        $query = $query->whereNull('subscribers.verification_status');
                    } else {
                        $query = $query->where('subscribers.verification_status', '=', $filters['verification_result']);
                    }
                }
            }

            // outside filters
            if (!empty($request->status)) {
                $query = $query->where('subscribers.status', '=', $request->status);
            }
            if (!empty($request->verification_result)) {
                if ($request->verification_result == 'unverified') {
                    $query = $query->whereNull('subscribers.verification_status');
                } else {
                    $query = $query->where('subscribers.verification_status', '=', $request->verification_result);
                }
            }

            // Open
            if ($request->open == 'yes') {
                $query = $query->whereExists(function ($q) {
                    $q->select(\DB::raw(1))
                        ->from('open_logs')
                        ->join('tracking_logs', 'open_logs.message_id', '=', 'tracking_logs.message_id')
                        ->whereRaw(table('tracking_logs').'.subscriber_id = '.table('subscribers').'.id');
                });
            }

            // Not Open
            if ($request->open == 'no') {
                $query = $query->whereNotExists(function ($q) {
                    $q->select(\DB::raw(1))
                        ->from('open_logs')
                        ->join('tracking_logs', 'open_logs.message_id', '=', 'tracking_logs.message_id')
                        ->whereRaw(table('tracking_logs').'.subscriber_id = '.table('subscribers').'.id');
                });
            }

            // Click
            if ($request->click == 'yes') {
                $query = $query->whereExists(function ($q) {
                    $q->select(\DB::raw(1))
                        ->from('click_logs')
                        ->join('tracking_logs', 'click_logs.message_id', '=', 'tracking_logs.message_id')
                        ->whereRaw(table('tracking_logs').'.subscriber_id = '.table('subscribers').'.id');
                });
            }

            // Not Click
            if ($request->click == 'no') {
                $query = $query->whereNotExists(function ($q) {
                    $q->select(\DB::raw(1))
                        ->from('click_logs')
                        ->join('tracking_logs', 'click_logs.message_id', '=', 'tracking_logs.message_id')
                        ->whereRaw(table('tracking_logs').'.subscriber_id = '.table('subscribers').'.id');
                });
            }
        }

        return $query;
    }

    /**
     * Get all languages.
     *
     * @return collect
     */
    public static function search($request, $customer = null)
    {
        $query = self::select('subscribers.*');

        // Filter by customer
        if (!isset($customer)) {
            $customer = $request->user()->customer;
        }
        $query = $query->where('mail_lists.customer_id', '=', $customer->id);

        // Filter
        $query = self::filter($query, $request);

        // Order
        if (isset($request->sort_order)) {
            $query = $query->orderBy($request->sort_order, $request->sort_direction);
        }

        return $query;
    }

    /**
     * Get field value by list field.
     *
     * @return value
     */
    public function getValueByField($field)
    {
        $fv = $this->subscriberFields->filter(function ($r, $key) use ($field) {
            return $r->field_id == $field->id;
        })->first();
        if (is_object($fv)) {
            return $fv->value;
        } else {
            return '';
        }
    }

    /**
     * Get field value by list field.
     *
     * @return value
     */
    public function getValueByTag($tag)
    {
        $fv = SubscriberField::leftJoin('fields', 'fields.id', '=', 'subscriber_fields.field_id')
            ->where('subscriber_id', '=', $this->id)->where('fields.tag', '=', $tag)->first();
        if (is_object($fv)) {
            return $fv->value;
        } else {
            return '';
        }
    }

    /**
     * Set field.
     *
     * @return value
     */
    public function setField($field, $value)
    {
        $fv = SubscriberField::where('subscriber_id', '=', $this->id)->where('field_id', '=', $field->id)->first();
        if (!is_object($fv)) {
            $fv = new SubscriberField();
            $fv->field_id = $field->id;
            $fv->subscriber_id = $this->id;
        }

        $fv->value = $value;
        $fv->save();
    }

    /**
     * Items per page.
     *
     * @var array
     */
    public static $itemsPerPage = 25;

    /**
     * Get secure code for updating subscriber.
     *
     * @param string $action
     */
    public function getSecurityToken($action)
    {
        $string = $this->email.$action.config('app.key');

        return md5($string);
    }

    /**
     * Create customer action log.
     *
     * @param string   $cat
     * @param Customer $customer
     * @param array    $add_datas
     */
    public function log($name, $customer, $add_datas = [])
    {
        $data = [
                'id' => $this->id,
                'email' => $this->email,
                'list_id' => $this->mail_list_id,
                'list_name' => $this->mailList->name,
        ];

        $data = array_merge($data, $add_datas);

        \Acelle\Model\Log::create([
                                'customer_id' => $customer->id,
                                'type' => 'subscriber',
                                'name' => $name,
                                'data' => json_encode($data),
                            ]);
    }

    /**
     * Copy to list.
     *
     * @param MailList $list
     */
    public function copy(MailList $list, Closure $duplicateCallback = null)
    {
        // find exists
        $copy = $list->subscribers()->where('email', '=', $this->email)->first();

        if (!is_null($copy)) {
            if (!is_null($duplicateCallback)) {
                $duplicateCallback($this);
            }

            return null;
        }

        // Actually copy
        $copy = self::find($this->id)->replicate();
        $copy->uid = uniqid();
        $copy->mail_list_id = $list->id;
        $copy->save();

        // update fields
        foreach ($this->subscriberFields as $item) {
            foreach ($copy->mailList->fields as $field) {
                if ($item->field->tag == $field->tag) {
                    $copy->setField($field, $item->value);
                }
            }
        }

        return $copy;
    }

    /**
     * Move to list.
     *
     * @param MailList $list
     */
    public function move($list)
    {
        $this->copy($list);
        $this->delete();
    }

    /**
     * Get tracking log.
     *
     * @param MailList $list
     */
    public function trackingLog($campaign)
    {
        $query = \Acelle\Model\TrackingLog::where('tracking_logs.subscriber_id', '=', $this->id);
        $query = $query->where('tracking_logs.campaign_id', '=', $campaign->id)->orderBy('created_at', 'desc')->first();

        return $query;
    }

    /**
     * Get all subscriber's open logs.
     *
     * @param MailList $list
     */
    public function openLogs($campaign = null)
    {
        $query = \Acelle\Model\OpenLog::leftJoin('tracking_logs', 'tracking_logs.message_id', '=', 'open_logs.message_id')
            ->where('tracking_logs.subscriber_id', '=', $this->id);

        if (isset($campaign)) {
            $query = $query->where('tracking_logs.campaign_id', '=', $campaign->id);
        }

        return $query;
    }

    /**
     * Get last open.
     *
     * @param MailList $list
     */
    public function lastOpenLog($campaign = null)
    {
        $query = $this->openLogs($campaign);

        $query = $query->orderBy('open_logs.created_at', 'desc')->first();

        return $query;
    }

    /**
     * Get all subscriber's click logs.
     *
     * @param MailList $list
     */
    public function clickLogs($campaign = null)
    {
        $query = \Acelle\Model\ClickLog::leftJoin('tracking_logs', 'tracking_logs.message_id', '=', 'click_logs.message_id')
            ->where('tracking_logs.subscriber_id', '=', $this->id);

        if (isset($campaign)) {
            $query = $query->where('tracking_logs.campaign_id', '=', $campaign->id);
        }

        return $query;
    }

    /**
     * Get last click.
     *
     * @param MailList $list
     */
    public function lastClickLog($campaign = null)
    {
        $query = $this->clickLogs();
        $query = $query->orderBy('click_logs.created_at', 'desc')->first();

        return $query;
    }

    /**
     * Is overide copy/move subscriber.
     *
     * return array
     */
    public static function copyMoveExistSelectOptions()
    {
        return [
            ['text' => trans('messages.update_if_subscriber_exists'), 'value' => 'update'],
            ['text' => trans('messages.keep_if_subscriber_exists'), 'value' => 'keep'],
        ];
    }

    /**
     * Verify subscriber email address using a given service.
     */
    public function verify($verifier)
    {
        list($status, $response) = $verifier->verify($this->email);
        $this->verification_status = $status;
        $this->last_verification_at = Carbon::now();
        $this->last_verification_by = $verifier->name;
        $this->last_verification_result = (string)$response->getBody();
        $this->save();
        return $this;
    }

    public function setVerificationStatus($status)
    {
        // note: status must be one of the pre-defined list: see related constants
        $this->verification_status = $status;
        $this->last_verification_at = Carbon::now();
        $this->last_verification_by = 'ADMIN';
        $this->last_verification_result = 'Manually set';
        $this->save();
        return $this;
    }

    /**
     * Reset subscriber verification.
     */
    public function resetVerification()
    {
        $this->verification_status = null;
        $this->last_verification_at = null;
        $this->last_verification_by = null;
        $this->last_verification_result = null;
        $this->save();
    }

    /**
     * Upload and resize avatar.
     *
     * @var string
     *
     * @return string
     */
    public function uploadImage($file)
    {
        $path = 'app/subscriber/';
        $upload_path = storage_path($path);

        if (!file_exists($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $filename = $this->uid.'.jpg';

        // save to server
        $file->move($upload_path, $filename);

        // create thumbnails
        $img = \Image::make($upload_path.$filename);
        $img->fit(120, 120)->save($upload_path.$filename);

        return $path.$filename;
    }

    /**
     * Get image thumb path.
     *
     * @return string
     */
    public function imagePath()
    {
        if (!empty($this->uid) && is_file(storage_path('app/subscriber/'.$this->uid).'.jpg')) {
            return storage_path('app/subscriber/'.$this->uid).'.jpg';
        } else {
            return '';
        }
    }

    /**
     * Remove thumb path.
     */
    public function removeImage()
    {
        if (!empty($this->uid)) {
            $path = storage_path('app/subscriber/'.$this->uid);
            if (is_file($path)) {
                unlink($path);
            }
            if (is_file($path.'.jpg')) {
                unlink($path.'.jpg');
            }
        }
    }

    /**
     * Check if the subscriber is listed in the Blacklist database.
     */
    public function isListedInBlacklist()
    {
        // @todo Filter by current user only
        return Blacklist::where('email', '=', $this->email)->exists();
    }

    public function getFullName($default = null)
    {
        $full = trim($this->getValueByTag('FIRST_NAME').' '.$this->getValueByTag('LAST_NAME'));
        if (empty($full)) {
            return $default;
        } else {
            return $full;
        }
    }

    public function getFullNameOrEmail()
    {
        $full = $this->getFullName();
        if (empty($full)) {
            return $this->email;
        } else {
            return $full;
        }
    }

    /**
     * Is the subscriber active?
     */
    public function isActive()
    {
        return $this->status == self::STATUS_SUBSCRIBED;
    }

    /**
     * Get tags.
     */
    public function getTags(): array
    {
        // Notice: json_decode() returns null if input is null or empty
        return json_decode($this->tags, true) ?: [];
    }

    /**
     * Get tags.
     */
    public function getTagOptions()
    {
        $arr = [];
        foreach ($this->getTags() as $tag) {
            $arr[] = ['text' => $tag, 'value' => $tag];
        }

        return $arr;
    }

    /**
     * Add tags.
     */
    public function addTags($arr)
    {
        $tags = $this->getTags();

        $nTags = array_values(array_unique(array_merge($tags, $arr)));

        $this->tags = json_encode($nTags);
        $this->save();
    }

    /**
     * Add tags.
     */
    public function updateTags(array $newTags, $merge = false)
    {
        // remove trailing space
        array_walk($newTags, function (&$val) {
            $val = trim($val);
        });

        // remove empty tag
        $newTags = array_filter($newTags, function (&$val) {
            return !empty($val);
        });

        if ($merge == true) {
            $currentTags = $this->getTags();
            $newTags = array_values(array_unique(array_merge($currentTags, $newTags)));
        }

        // Without JSON_UNESCAPED_UNICODE specified
        // Results of json_encode(['русский']) may look like this
        //
        //     ["\u0440\u0443\u0441\u0441\u043a\u0438\u0439"]
        //
        // which cannot be searched for
        //
        $this->tags = json_encode($newTags, JSON_UNESCAPED_UNICODE);
        $this->save();
    }

    /**
     * Remove tag.
     */
    public function removeTag($tag)
    {
        $tags = $this->getTags();

        if (($key = array_search($tag, $tags)) !== false) {
            unset($tags[$key]);
        }

        $this->tags = json_encode($tags);
        $this->save();
    }

    /**
     * Filter items.
     *
     * @return collect
     */
    public function scopeFilter($query, $request)
    {
        if (isset($request)) {
            // filters
            $filters = $request->filters;
            if (!empty($filters)) {
                if (!empty($filters['status'])) {
                    $query = $query->where('subscribers.status', '=', $filters['status']);
                }
                if (!empty($filters['verification_result'])) {
                    if ($filters['verification_result'] == 'unverified') {
                        $query = $query->whereNull('subscribers.verification_status');
                    } else {
                        $query = $query->where('subscribers.verification_status', '=', $filters['verification_result']);
                    }
                }
            }

            // outside filters
            if (!empty($request->status)) {
                $query = $query->where('subscribers.status', '=', $request->status);
            }
            if (!empty($request->verification_result)) {
                $query = $query->where('subscribers.verification_status', '=', $request->verification_result);
            }

            // Open
            if ($request->open == 'yes') {
                $query = $query->whereExists(function ($q) {
                    $q->select(\DB::raw(1))
                        ->from('open_logs')
                        ->join('tracking_logs', 'open_logs.message_id', '=', 'tracking_logs.message_id')
                        ->whereRaw(table('tracking_logs').'.subscriber_id = '.table('subscribers').'.id');
                });
            }

            // Not Open
            if ($request->open == 'no') {
                $query = $query->whereNotExists(function ($q) {
                    $q->select(\DB::raw(1))
                        ->from('open_logs')
                        ->join('tracking_logs', 'open_logs.message_id', '=', 'tracking_logs.message_id')
                        ->whereRaw(table('tracking_logs').'.subscriber_id = '.table('subscribers').'.id');
                });
            }

            // Click
            if ($request->click == 'yes') {
                $query = $query->whereExists(function ($q) {
                    $q->select(\DB::raw(1))
                        ->from('click_logs')
                        ->join('tracking_logs', 'click_logs.message_id', '=', 'tracking_logs.message_id')
                        ->whereRaw(table('tracking_logs').'.subscriber_id = '.table('subscribers').'.id');
                });
            }

            // Not Click
            if ($request->click == 'no') {
                $query = $query->whereNotExists(function ($q) {
                    $q->select(\DB::raw(1))
                        ->from('click_logs')
                        ->join('tracking_logs', 'click_logs.message_id', '=', 'tracking_logs.message_id')
                        ->whereRaw(table('tracking_logs').'.subscriber_id = '.table('subscribers').'.id');
                });
            }
        }

        return $query;
    }

    /**
     * Get all languages.
     *
     * @return collect
     */
    public function scopeSearch($query, $keyword)
    {
        /* does not support searching on subscriber fields, for the sake of performance
        $query = $query->leftJoin('subscriber_fields', 'subscribers.id', '=', 'subscriber_fields.subscriber_id')
            ->leftJoin('mail_lists', 'subscribers.mail_list_id', '=', 'mail_lists.id');
        */
        // Keyword
        if (!empty(trim($keyword))) {
            foreach (explode(' ', trim($keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('subscribers.email', 'like', '%'.$keyword.'%');
                    /* does not support searching on subscriber fields, for the sake of performance
                    ->orWhere('subscriber_fields.value', 'like', '%'.$keyword.'%');
                    */
                });
            }
        }

        return $query;
    }

    public function scopeSubscribed($query)
    {
        return $query->where('subscribers.status', self::STATUS_SUBSCRIBED);
    }

    public function isSubscribed()
    {
        return $this->status == self::STATUS_SUBSCRIBED;
    }

    public function isUnsubscribed()
    {
        return $this->status == self::STATUS_UNSUBSCRIBED;
    }

    public function getHistory()
    {
        $openLogs = table('open_logs');
        $clickLogs = table('click_logs');
        $subscribeLogs = table('subscribe_logs');
        $subscribers = table('subscribers');
        $mailLists = table('mail_lists');
        $campaigns = table('campaigns');
        $trackingLogs = table('tracking_logs');

        $sql = "
            SELECT subscriber_id, activity, list_id, list_name, campaign_id, campaign_name, at
            FROM
            (
                SELECT t.subscriber_id, 'open' as activity, null as list_id, null as list_name, t.campaign_id, c.name as campaign_name, open.created_at as at
                FROM {$openLogs} open
                JOIN {$trackingLogs} t on open.message_id = t.message_id
                JOIN {$subscribers} s on s.id = t.subscriber_id
                JOIN {$campaigns} c on c.id  = t.campaign_id
                WHERE s.email = '{$this->email}'
            ) AS open

            UNION
            (
                SELECT t.subscriber_id, 'click' as activity, null as list_id, null as list_name, t.campaign_id, c.name as campaign_name, click.created_at as at
                FROM {$clickLogs} click
                JOIN {$trackingLogs} t on click.message_id = t.message_id
                JOIN {$subscribers} s on s.id = t.subscriber_id
                JOIN {$campaigns} c on c.id  = t.campaign_id
                WHERE s.email = '{$this->email}'
            )

            UNION
            (
                SELECT s.id AS subscriber_id, 'subscribe' AS activity, l.id as list_id, l.name as list_name, null AS campaign_id, null AS campaign_name, s.created_at as at
                FROM {$subscribers} s
                JOIN {$mailLists} l on l.id  = s.mail_list_id
                WHERE s.email = '{$this->email}'
            )

            ORDER BY at DESC;
        ";

        $result = DB::select($sql);

        return json_decode(json_encode($result), true);
    }

    public function scopeSearchByEmail($query, $email)
    {
        return $query->where('subscribers.email', $email);
    }

    public function reformatDateFields()
    {
        $this->mailList->reformatDateFields($this->id);
    }

    /**
     * assgin values.
     */
    public static function assginValues($subscribers, $request)
    {
        if ($request->assign_type == 'single') {
            $rules = [
                'single_value' => 'required',
            ];
        } else {
            $rules = [
                'list_value' => 'required',
            ];
        }
        // make validator
        $validator = \Validator::make($request->all(), $rules);

        // redirect if fails
        if ($validator->fails()) {
            return $validator;
        }

        // do assign
        if ($request->assign_type == 'single') {
            // do assign a value: $request->single_value
        } else {
            // do assign a list: $request->list_value
        }

        return $validator;
    }


    // Confirm a subscription via double opt-in form
    public function confirm()
    {
        $this->status = self::STATUS_SUBSCRIBED;
        $this->save();

        MailListSubscription::dispatch($this);
    }

    public function scopeUnsubscribed($query)
    {
        return $query->where('status', '=', self::STATUS_UNSUBSCRIBED);
    }

    public function generateUnsubscribeUrl($messageId = null, $absoluteUrl = true)
    {
        $url = route('unsubscribeUrl', [
            'message_id' => StringHelper::base64UrlEncode($messageId),
            'subscriber' => $this->uid
        ], $absoluteUrl);

        return $url;
    }

    public function generateUpdateProfileUrl()
    {
        return route('updateProfileUrl', ['list_uid' => $this->mailList->uid, 'uid' => $this->uid, 'code' => $this->getSecurityToken('update-profile')]);
    }

    // Change status to SUBSCRIBED
    // @important: need subscription log in the future?
    public function subscribe()
    {
        $this->status = self::STATUS_SUBSCRIBED;
        $this->save();

        MailListSubscription::dispatch($this);
    }

    public function scopeSimpleSearch($query, $keyword)
    {
        if (empty($keyword)) {
            return $query;
        }

        $cleanKeyword = preg_replace('/[^a-z0-9_\.@]+/i', ' ', $keyword);

        return $query->where(function ($query) use ($cleanKeyword) {
            $query->where('subscribers.email', 'LIKE', "%{$cleanKeyword}%");
        });
    }

    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Check if the email address is deliverable.
     *
     * @return bool
     */
    public function isDeliverable()
    {
        return $this->verification_result == self::VERIFICATION_STATUS_DELIVERABLE;
    }

    /**
     * Check if the email address is undeliverable.
     *
     * @return bool
     */
    public function isUndeliverable()
    {
        return $this->verification_result == self::VERIFICATION_STATUS_UNDELIVERABLE;
    }

    /**
     * Check if the email address is risky.
     *
     * @return bool
     */
    public function isRisky()
    {
        return $this->verification_result == self::VERIFICATION_STATUS_RISKY;
    }

    /**
     * Check if the email address is unknown.
     *
     * @return bool
     */
    public function isUnknown()
    {
        return $this->verification_result == self::VERIFICATION_STATUS_UNKNOWN;
    }

    /**
     * Email verification result types select options.
     *
     * @return array
     */
    public static function getVerificationStates()
    {
        return [
            ['value' => self::VERIFICATION_STATUS_DELIVERABLE, 'text' => trans('messages.email_verification_result_deliverable')],
            ['value' => self::VERIFICATION_STATUS_UNDELIVERABLE, 'text' => trans('messages.email_verification_result_undeliverable')],
            ['value' => self::VERIFICATION_STATUS_UNKNOWN, 'text' => trans('messages.email_verification_result_unknown')],
            ['value' => self::VERIFICATION_STATUS_RISKY, 'text' => trans('messages.email_verification_result_risky')],
            ['value' => self::VERIFICATION_STATUS_UNVERIFIED, 'text' => trans('messages.email_verification_result_unverified')],
        ];
    }
}
