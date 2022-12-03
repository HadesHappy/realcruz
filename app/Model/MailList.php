<?php

/**
 * MailList class.
 *
 * Model class for log mail list
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
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Acelle\Library\Log as MailLog;
use Acelle\Library\RouletteWheel;
use Acelle\Library\StringHelper;
use Acelle\Library\ExtendedSwiftMessage;
use Acelle\Events\MailListSubscription;
use Acelle\Events\MailListUpdated;
use Acelle\Model\Setting;
use Acelle\Model\SubscriberFields;
use Acelle\Library\Traits\QueryHelper;
use Acelle\Events\MailListImported;
use Acelle\Library\Traits\TrackJobs;
use DB;
use App;
use File;
use Exception;
use Acelle\Jobs\ImportSubscribersJob;
use Acelle\Jobs\ImportSubscribers2;
use Acelle\Jobs\ExportSubscribersJob;
use Acelle\Jobs\VerifyMailListJob;
use League\Csv\Writer;
use Acelle\Library\Traits\HasUid;

class MailList extends Model
{
    use QueryHelper;
    use TrackJobs;
    use HasUid;

    public const SOURCE_EMBEDDED_FORM = 'embedded-form';
    public const SOURCE_WEB = 'web';
    public const SOURCE_API = 'api';

    public const IMPORT_TEMP_DIR = 'app/tmp/import/';
    public const EXPORT_TEMP_DIR = 'app/tmp/export/';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'default_subject', 'from_email', 'from_name',
        'remind_message', 'send_to', 'email_daily', 'email_subscribe',
        'email_unsubscribe', 'send_welcome_email', 'unsubscribe_notification',
        'subscribe_confirmation', 'all_sending_servers',
    ];

    /**
     * The rules for validation.
     *
     * @var array
     */
    public static $rules = array(
        'name' => 'required',
        'from_email' => 'required|email',
        'from_name' => 'required',
        'default_subject' => 'required',
        'contact.company' => 'required',
        'contact.address_1' => 'required',
        'contact.country_id' => 'required',
        'contact.state' => 'required',
        'contact.city' => 'required',
        'contact.zip' => 'required',
        'contact.phone' => 'required',
        'contact.email' => 'required|email',
        'contact.url' => 'nullable|regex:/^https{0,1}:\/\//',
        'email_subscribe' => 'nullable|regex:"^[\W]*([\w+\-.%]+@[\w\-.]+\.[A-Za-z]{2,4}[\W]*,{1}[\W]*)*([\w+\-.%]+@[\w\-.]+\.[A-Za-z]{2,4})[\W]*$"',
        'email_unsubscribe' => 'nullable|regex:"^[\W]*([\w+\-.%]+@[\w\-.]+\.[A-Za-z]{2,4}[\W]*,{1}[\W]*)*([\w+\-.%]+@[\w\-.]+\.[A-Za-z]{2,4})[\W]*$"',
        'email_daily' => 'nullable|regex:"^[\W]*([\w+\-.%]+@[\w\-.]+\.[A-Za-z]{2,4}[\W]*,{1}[\W]*)*([\w+\-.%]+@[\w\-.]+\.[A-Za-z]{2,4})[\W]*$"',
    );

    // Server pools
    public static $serverPools = array();
    public static $itemsPerPage = 25;
    protected $currentSubscription;
    protected $sendingSevers = null;

    /**
     * Associations.
     *
     * @var object | collect
     */
    public function fields()
    {
        return $this->hasMany('Acelle\Model\Field');
    }

    public function customer()
    {
        return $this->belongsTo('Acelle\Model\Customer');
    }

    public function segments()
    {
        return $this->hasMany('Acelle\Model\Segment');
    }

    public function automations()
    {
        return $this->hasMany('Acelle\Model\Automation2');
    }

    public function pages()
    {
        return $this->hasMany('Acelle\Model\Page');
    }

    public function page($layout)
    {
        return $this->pages()->where('layout_id', $layout->id)->first();
    }

    public function contact()
    {
        return $this->belongsTo('Acelle\Model\Contact');
    }

    public function subscribers()
    {
        return $this->hasMany('Acelle\Model\Subscriber');
    }

    public function campaigns()
    {
        return $this->belongsToMany('Acelle\Model\Campaign', 'campaigns_lists_segments', 'mail_list_id', 'campaign_id');
    }

    public function subscriberFields()
    {
        return $this->hasManyThrough(SubscriberField::class, Field::class);
    }

    /**
     * Bootstrap any application services.
     */
    public static function boot()
    {
        parent::boot();

        // Create uid when creating list.
        static::creating(function ($item) {
            // Create new uid
            $uid = uniqid();
            $item->uid = $uid;
        });

        // Create uid when list created.
        static::created(function ($item) {
            //  Create list default fields
            $item->createDefaultFieds();
        });

        // detele
        static::deleted(function ($item) {
            //  Delete contact when list deleted
            if (!is_null($item->contact)) {
                $item->contact->delete();
            }

            // Delete export jobs
            $item->exportJobs()->delete();
        });
    }

    /**
     * Get all items.
     *
     * @return collect
     */
    public static function getAll()
    {
        return self::select('*');
    }

    public function uploadCsv(\Illuminate\Http\UploadedFile $httpFile)
    {
        $filename = "import-".uniqid().".csv";

        // store it to storage/
        $httpFile->move($this->getImportFilePath(), $filename);

        // Example of outcome: /home/acelle/storage/app/tmp/import-000000.csv
        $filepath = $this->getImportFilePath($filename);

        return $filepath;
    }

    public function dispatchVerificationJob($server)
    {
        if (is_null($server)) {
            throw new Exception('Cannot start job: empty verification server');
        }

        $job = new VerifyMailListJob($this, $server);
        $monitor = $this->dispatchWithBatchMonitor(
            $job,
            $then = null,
            $catch = null,
            $finally = null
        );

        return $monitor;
    }

    public function dispatchImportJob($filepath)
    {
        // Example: /home/acelle/storage/app/tmp/import-000000.csv
        $job = new ImportSubscribersJob($this, $filepath);
        $monitor = $this->dispatchWithMonitor($job);
        return $monitor;
    }

    public function getImportTempDir($file = null)
    {
        $base = storage_path(self::IMPORT_TEMP_DIR);
        if (!File::exists($base)) {
            File::makeDirectory($base, 0777, true, true);
        }

        return join_paths($base, $file);
    }

    public function getExportTempDir($file = null)
    {
        $base = storage_path(self::EXPORT_TEMP_DIR);
        if (!File::exists($base)) {
            File::makeDirectory($base, 0777, true, true);
        }

        return join_paths($base, $file);
    }

    public function getImportFilePath($filename = null)
    {
        return $this->getImportTempDir($filename);
    }

    public static function scopeSearch($query, $keyword)
    {
        // Keyword
        if (!empty(trim($keyword))) {
            $query = $query->where('name', 'like', '%'.$keyword.'%');
        }
    }

    /**
     * Get all fields.
     *
     * @return object
     */
    public function getFields()
    {
        return $this->fields();
    }

    public function getDateOrDateTimeFields()
    {
        return $this->getFields()->whereIn('type', ['datetime', 'date']);
    }

    /**
     * Create default fields for list.
     */
    public function createDefaultFieds()
    {
        $this->fields()->create([
                            'mail_list_id' => $this->id,
                            'type' => 'text',
                            'label' => trans('messages.email'),
                            'tag' => 'EMAIL',
                            'required' => true,
                            'visible' => true,
                        ]);

        $this->fields()->create([
                            'mail_list_id' => $this->id,
                            'type' => 'text',
                            'label' => trans('messages.first_name'),
                            'tag' => \Acelle\Model\Field::formatTag(trans('messages.first_name_tag')),
                            'required' => false,
                            'visible' => true,
                        ]);

        $this->fields()->create([
                            'mail_list_id' => $this->id,
                            'type' => 'text',
                            'label' => trans('messages.last_name'),
                            'tag' => \Acelle\Model\Field::formatTag(trans('messages.last_name_tag')),
                            'required' => false,
                            'visible' => true,
                        ]);
    }

    /**
     * Get email field.
     *
     * @return object
     */
    public function getEmailField()
    {
        return $this->getFieldByTag('EMAIL');
    }

    /**
     * Get field by tag.
     *
     * @return object
     */
    public function getFieldByTag($tag)
    {
        // Case insensitive search
        return $this->fields()->where(DB::raw('LOWER(tag)'), '=', strtolower($tag))->first();
    }

    /**
     * Get field by tag.
     *
     * @return object
     */
    public function activeSubscribers()
    {
        return $this->subscribers()->where('subscribers.status', 'subscribed');
    }

    /**
     * Get field by tag.
     *
     * @return object
     */
    public function getActiveSubscribers()
    {
        return $this->activeSubscribers()->get();
    }

    /**
     * Get field rules.
     *
     * @return object
     */
    public function getFieldRules()
    {
        $rules = [];
        foreach ($this->getFields as $field) {
            if ($field->tag == 'EMAIL') {
                $rules[$field->tag] = 'required|email:rfc,filter';
            } elseif ($field->required) {
                $rules[$field->tag] = 'required';
            }
        }

        return $rules;
    }

    /**
     * Reset the sending server pool.
     *
     * @return mixed
     */
    public static function resetServerPools()
    {
        self::$serverPools = array();
    }

    /**
     * Check if a email is exsit.
     *
     * @param string the email
     *
     * @return bool
     */
    public function checkExsitEmail($email)
    {
        $valid = !filter_var($email, FILTER_VALIDATE_EMAIL) === false &&
            !empty($email) &&
            $this->subscribers()->where('email', '=', $email)->count() == 0;

        return $valid;
    }

    /**
     * Get segments select options.
     *
     * @return array
     */
    public function getSegmentSelectOptions($cache = false)
    {
        $options = $this->segments->map(function ($item) use ($cache) {
            return ['value' => $item->uid, 'text' => $item->name.' ('.$item->subscribersCount($cache).' '.strtolower(trans('messages.subscribers')).')'];
        });

        return $options;
    }

    /**
     * Count unsubscribe.
     *
     * @return array
     */
    public function unsubscribeCount()
    {
        // return distinctCount($this->subscribers()->unsubscribed(), 'subscribers.email');
        return $this->subscribers()->unsubscribed()->count();
    }

    /**
     * Unsubscribe rate.
     *
     * @return array
     */
    public function unsubscribeRate($cache = false)
    {
        $count = $this->subscribersCount($cache);
        if ($count == 0) {
            return 0;
        }

        return round($this->unsubscribeCount() / $count, 2);
    }

    /**
     * Count unsubscribe.
     *
     * @return array
     */
    public function subscribeCount()
    {
        // return distinctCount($this->subscribers()->where('status', '=', 'subscribed'), 'subscribers.email');
        return $this->subscribers()->where('status', '=', 'subscribed')->count();
    }

    /**
     * Unsubscribe rate.
     *
     * @return array
     */
    public function subscribeRate($cache = false)
    {
        $count = $this->subscribersCount($cache);
        if ($count == 0) {
            return 0;
        }

        return round($this->subscribeCount() / $count, 2);
    }

    /**
     * Count unsubscribe.
     *
     * @return array
     */
    public function unconfirmedCount()
    {
        // return distinctCount($this->subscribers()->where('status', '=', 'unconfirmed'), 'subscribers.email');
        return $this->subscribers()->where('status', '=', 'unconfirmed')->count();
    }

    /**
     * Count blacklisted.
     *
     * @return array
     */
    public function blacklistedCount()
    {
        // return distinctCount($this->subscribers()->where('status', '=', 'blacklisted'), 'subscribers.email');
        return $this->subscribers()->where('status', '=', 'blacklisted')->count();
    }

    /**
     * Count blacklisted.
     *
     * @return array
     */
    public function spamReportedCount()
    {
        // return distinctCount($this->subscribers()->where('status', '=', 'spam-reported'), 'subscribers.email');
        return $this->subscribers()->where('status', '=', 'spam-reported')->count();
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
            'type' => 'list',
            'name' => $name,
            'data' => json_encode($data)
        ]);
    }

    /**
     * Open count.
     */
    public function openCount()
    {
        $query = OpenLog::join('tracking_logs', 'tracking_logs.message_id', '=', 'open_logs.message_id')
            ->whereIn('tracking_logs.subscriber_id', function ($query) {
                $query->select('subscribers.id')
                    ->from('subscribers')
                    ->where('subscribers.mail_list_id', '=', $this->id);
            });

        return $query->count();
    }

    /**
     * Get list click logs.
     *
     * @return mixed
     */
    public function clickLogs()
    {
        $query = ClickLog::join('tracking_logs', 'tracking_logs.message_id', '=', 'click_logs.message_id')
            ->whereIn('tracking_logs.subscriber_id', function ($query) {
                $query->select('subscribers.id')
                    ->from('subscribers')
                    ->where('subscribers.mail_list_id', '=', $this->id);
            });

        return $query;
    }

    /**
     * Open count.
     */
    public function clickCount()
    {
        $query = $this->clickLogs();

        return $query->distinct('url')->count('url');
    }

    /**
     * Open count.
     */
    public function openUniqCount()
    {
        $query = OpenLog::join('tracking_logs', 'tracking_logs.message_id', '=', 'open_logs.message_id')
            ->whereIn('tracking_logs.subscriber_id', function ($query) {
                $query->select('subscribers.id')
                    ->from('subscribers')
                    ->where('subscribers.mail_list_id', '=', $this->id);
            });

        return $query->distinct('subscriber_id')->count('subscriber_id');
    }

    /**
     * Tracking count.
     */
    public function trackingCount()
    {
        $query = TrackingLog::whereIn('tracking_logs.subscriber_id', function ($query) {
            $query->select('subscribers.id')
                    ->from('subscribers')
                    ->where('subscribers.mail_list_id', '=', $this->id);
        });

        return $query->count();
    }

    /**
     * Count open uniq rate.
     *
     * @return number
     */
    public function openUniqRate()
    {
        $subscribersCount = $this->subscribers()->count();
        if ($subscribersCount == 0) {
            return 0.0;
        }

        return round(($this->openUniqCount() / $subscribersCount), 2);
    }

    /**
     * Count click rate.
     *
     * @return number
     */
    public function clickRate()
    {
        $open_count = $this->openCount();
        if ($open_count == 0) {
            return 0;
        }

        return round(($this->clickedEmailsCount() / $open_count), 2);
    }

    /**
     * Count unique clicked opened emails.
     *
     * @return number
     */
    public function clickedEmailsCount()
    {
        $query = $this->clickLogs();

        return $query->distinct('subscriber_id')->count('subscriber_id');
    }

    /**
     * Get other lists.
     *
     * @return number
     */
    public function otherLists()
    {
        return $this->customer
            ->mailLists()
            ->orderBy('mail_lists.name', 'asc')
            ->where('id', '!=', $this->id)->get();
    }

    /**
     * Get name with subscrbers count.
     *
     * @return number
     */
    public function longName($cache = false)
    {
        $count = $this->subscribersCount($cache);

        return $this->name.' - '.$count.' '.trans('messages.'.\Acelle\Library\Tool::getPluralPrase('subscriber', $count)).'';
    }

    /**
     * Copy new list.
     */
    public function copy($name, $customer = null)
    {
        $copy = $this->replicate(['cache']);
        $copy->uid = uniqid();
        $copy->name = $name;
        $copy->created_at = \Carbon\Carbon::now();
        $copy->updated_at = \Carbon\Carbon::now();

        if ($customer) {
            $copy->customer_id = $customer->id;
        }

        $copy->save();

        // @todo: trigger MailListSubscription event here?

        // Contact
        if (is_object($this->contact)) {
            $new_contact = $this->contact->replicate();
            $new_contact->uid = uniqid();
            $new_contact->save();

            // update contact
            $copy->contact_id = $new_contact->id;
            $copy->save();
        }

        // Remove default fields
        $copy->fields()->delete();
        // Fields
        foreach ($this->fields as $field) {
            $new_field = $field->replicate();
            $new_field->mail_list_id = $copy->id;
            $new_field->save();

            // Copy field options
            foreach ($field->fieldOptions as $option) {
                $new_option = $option->replicate();
                $new_option->field_id = $new_field->id;
                $new_option->save();
            }
        }

        // update cache
        $copy->updateCache();

        return $copy;
    }

    public function getExportFilePath()
    {
        $name = preg_replace('/[^a-zA-Z0-9_\-\.]+/', '_', "{$this->uid}-{$this->name}.csv");
        return $this->getExportTempDir($name);
    }

    public function dispatchExportJob($segment = null)
    {
        return $this->dispatchWithMonitor(new ExportSubscribersJob($this, $segment));
    }

    /**
     * Export subscribers.
     */
    public function export($progressCallback = null, $segment = null)
    {
        $processed = 0;
        $total = 0;
        $message = null;
        $failed = 0;

        $pageSize = 1000;

        if (!is_null($progressCallback)) {
            $progressCallback($processed, $total, $failed, $message = 'Export is in progress...');
        }

        $file = $this->getExportFilePath();
        if (file_exists($file)) {
            File::delete($file);
        }

        // CSV writer
        $writer = Writer::createFromPath($file, 'w+');

        // Query subscribers
        $query = (is_null($segment)) ? $this->subscribers() : $segment->subscribers();
        $total = $query->count();

        // Get an associative array of [ 'tag' => null ]
        // Trick: pass '' as 1st parameter to have it result in null value
        $listFields = $this->fields->pluck('', 'tag')->toArray();

        // write the header
        $headers = array_keys($listFields);

        // Additional attributes
        $headers[] = 'status';
        $headers[] = 'uid';
        $headers[] = 'created_at';
        $headers[] = 'updated_at';

        $writer->insertOne($headers);

        // Iterate through the list and write to file, 1000 records each time
        cursorIterate($query, $orderBy = 'subscribers.id', $pageSize, function ($subscribers, $page) use ($writer, $listFields, &$processed, &$total, &$failed, &$message, $pageSize, $progressCallback) {
            $records = collect($subscribers)->map(function ($subscriber) use ($listFields) {
                $attributes = $subscriber->subscriberFields()
                                         ->orderBy('fields.id', 'ASC')
                                         ->join('fields', 'fields.id', 'subscriber_fields.field_id')
                                         ->pluck('subscriber_fields.value', 'fields.tag')->toArray();

                // Only take pairs that match the listFields' keys
                $attributes = array_intersect_key($attributes, $listFields);

                // merge the valid attributes to the base list of all keys
                // results in something like [ 'EMAIL' => 'example@example.io', 'OTHER' => null ]
                $attributes = array_merge($listFields, $attributes);

                $attributes['status'] = $subscriber->status;
                $attributes['uid'] = $subscriber->uid;
                $attributes['created_at'] = $subscriber->created_at->toString();
                $attributes['updated_at'] = $subscriber->updated_at->toString();

                // return the filtered array
                return $attributes;
            })->toArray();

            // Write the batch to file (append mode)
            $writer->insertAll($records);

            // Increment
            $processed += sizeof($records);

            // Callback
            if (!is_null($progressCallback)) {
                $progressCallback($processed, $total, $failed = 0, $message = sprintf('Export is in progress, %s / %s records written', $processed, $total));
            }
        });

        if (!is_null($progressCallback)) {
            $progressCallback($processed, $total, $failed = 0, $message = sprintf('Export complete, processed: %s / %s', $processed, $total));
        }
    }

    /**
     * Export Segments.
     */
    public static function exportSegments($list, $customer, $job)
    {
        // todo
    }

    /**
     * Send subscription confirmation email to subscriber.
     */
    public function sendSubscriptionConfirmationEmail($subscriber)
    {
        if ($subscriber->isListedInBlacklist()) {
            MailLog::info($subscriber->email.' is already blacklisted.');
            throw new \Exception(trans('messages.subscriber.blacklisted'));
        }

        if (Setting::isYes('verify_subscriber_email')) {
            MailLog::info('Verifying subscriber email: '.$subscriber->email);
            // @important: the user must have its own verification server, this will not work for system verification server (even if the user has access to)
            $verifier = $this->customer->getEmailVerificationServers()->first();

            if (is_null($verifier)) {
                MailLog::info(sprintf('Contact %s (%s) tries to subscribe to list %s (%s) but there is no verification service available', $subscriber->email, $subscriber->uid, $this->name, $this->uid));
                throw new \Exception(trans('messages.subscriber.email.fail_to_verify'));
            }

            if (!$subscriber->verify($verifier)->isDeliverable()) {
                MailLog::info(sprintf('Contact %s (%s) tries to subscribe to list %s (%s) but email address is invalid', $subscriber->email, $subscriber->uid, $this->name, $this->uid));
                throw new \Exception(trans('messages.subscriber.email.invalid'));
            }
        }

        MailLog::info('Sending subscription confirmation email to '.$subscriber->email);
        $list = $this;

        $layout = \Acelle\Model\Layout::where('alias', 'sign_up_confirmation_email')->first();
        $send_page = \Acelle\Model\Page::findPage($list, $layout);
        $send_page->renderContent(null, $subscriber);
        $this->sendMail($subscriber, $send_page, $send_page->getTransformedSubject($subscriber));
        MailLog::info('Sent subscription confirmation email to '.$subscriber->email);
    }

    /**
     * Send list related email.
     */
    public function send($message, $params = [])
    {
        $server = $this->pickSendingServer();
        $message->getHeaders()->addTextHeader('X-Acelle-Message-Id', StringHelper::generateMessageId(StringHelper::getDomainFromEmail($this->from_email)));

        return $server->send($message, $params);
    }

    /**
     * Send subscription confirmation email to subscriber.
     */
    public function sendSubscriptionWelcomeEmail($subscriber)
    {
        $list = $this;

        $layout = \Acelle\Model\Layout::where('alias', 'sign_up_welcome_email')->first();
        $send_page = \Acelle\Model\Page::findPage($list, $layout);
        $this->sendMail($subscriber, $send_page, $send_page->getTransformedSubject($subscriber));
    }

    /**
     * Send unsubscription goodbye email to subscriber.
     */
    public function sendUnsubscriptionNotificationEmail($subscriber)
    {
        $list = $this;

        $layout = \Acelle\Model\Layout::where('alias', 'unsubscribe_goodbye_email')->first();
        $send_page = \Acelle\Model\Page::findPage($list, $layout);
        $this->sendMail($subscriber, $send_page, $send_page->getTransformedSubject($subscriber));
    }

    public function sendSubscriptionNotificationEmailToListOwner($subscriber)
    {
        $template = Layout::where('alias', 'subscribe_notification_for_list_owner')->first();

        $message = $template->getMessage(function ($html) use ($subscriber) {
            $html = str_replace('{LIST_NAME}', $this->name, $html);
            $html = str_replace('{EMAIL}', $subscriber->email, $html);
            $html = str_replace('{FULL_NAME}', $subscriber->getFullName(), $html);

            return $html;
        });

        $message->setSubject($template->subject);
        $message->setTo([ $this->customer->user->email => $this->customer->user->displayName()]);
        $mailer = App::make('xmailer');
        $result = $mailer->sendWithDefaultFromAddress($message);

        if (array_key_exists('error', $result)) {
            throw new \Exception("Error sending unsubscribe notification: ".$result['error']);
        }
    }

    /**
     * Send unsubscription goodbye email to list owner.
     */
    public function sendUnsubscriptionNotificationEmailToListOwner($subscriber)
    {
        // Create a message
        $message = new ExtendedSwiftMessage();
        $message->setContentType('text/html; charset=utf-8');
        $message->setEncoder(new \Swift_Mime_ContentEncoder_PlainContentEncoder('8bit'));
        $message->setTo([ $this->customer->user->email => $this->customer->user->displayName()]);

        $template = \Acelle\Model\Layout::where('alias', 'unsubscribe_notification_for_list_owner')->first();
        $htmlContent = $template->content;

        $htmlContent = str_replace('{LIST_NAME}', $this->name, $htmlContent);
        $htmlContent = str_replace('{EMAIL}', $subscriber->email, $htmlContent);
        $htmlContent = str_replace('{FULL_NAME}', $subscriber->getFullName(), $htmlContent);

        $message->setSubject($template->subject);
        $message->addPart($htmlContent, 'text/html');

        $mailer = App::make('xmailer');
        MailLog::info('Sending unsubscription notification to list owner: '.$this->customer->user->email);
        $result = $mailer->sendWithDefaultFromAddress($message);

        if (array_key_exists('error', $result)) {
            throw new \Exception("Error sending unsubscribe notification: ".$result['error']);
        }
    }

    /**
     * Send unsubscription goodbye email to subscriber.
     */
    public function sendProfileUpdateEmail($subscriber)
    {
        $list = $this;

        $layout = \Acelle\Model\Layout::where('alias', 'profile_update_email')->first();
        $send_page = \Acelle\Model\Page::findPage($list, $layout);
        $this->sendMail($subscriber, $send_page, $send_page->getTransformedSubject($subscriber));
    }

    /**
     * Get date | datetime fields.
     *
     * @return array
     */
    public function getDateFields()
    {
        return $this->getFields()->whereIn('type', ['date', 'datetime'])->get();
    }

    /**
     * Get subscriber's fields select options.
     *
     * @return array
     */
    public function getSubscriberFieldSelectOptions()
    {
        $options = [];
        $options[] = ['text' => trans('messages.subscriber_subscription_date'), 'value' => 'subscription_date'];
        foreach ($this->getDateFields() as $field) {
            $options[] = ['text' => trans('messages.subscriber_s_field', ['name' => $field->label]), 'value' => $field->uid];
        }

        return $options;
    }

    /**
     * Get subscriber's fields select options.
     *
     * @return array
     */
    public function getFieldSelectOptions()
    {
        $options = [];
        foreach ($this->getFields()->get() as $field) {
            $options[] = ['text' => $field->label, 'value' => $field->uid];
        }

        return $options;
    }

    /**
     * Read a CSV file, returning the meta information.
     *
     * @param string file path
     *
     * @return array [$headers, $availableFields, $total, $results]
     */
    public function getRemainingAddSubscribersQuota()
    {
        $max = $this->customer->getOption('subscriber_max');
        $maxPerList = $this->customer->getOption('subscriber_per_list_max');

        $remainingForList = $maxPerList - $this->refresh()->subscribers->count();
        $remaining = $max - $this->refresh()->customer->subscribersCount(); // no cache

        if ($maxPerList == -1) {
            return ($max == -1) ? -1 : $remaining;
        }

        if ($max == -1) {
            return ($maxPerList == -1) ? -1 : $remainingForList;
        }

        return ($remainingForList > $remaining) ? $remaining : $remainingForList;
    }

    /**
     * Read a CSV file, returning the meta information.
     *
     * @param string file path
     *
     * @return array [$headers, $availableFields, $total, $results]
     */
    private function readCsv($file)
    {
        try {
            // Fix the problem with MAC OS's line endings
            if (!ini_get('auto_detect_line_endings')) {
                ini_set('auto_detect_line_endings', '1');
            }

            // return false or an encoding name
            $encoding = \Acelle\Library\StringHelper::detectEncoding($file);

            if ($encoding == false) {
                MailLog::warning("Cannot detect file's encoding: {$file}");
            } elseif ($encoding != 'UTF-8') {
                MailLog::warning("Convert from {$encoding} to UTF-8");
                \Acelle\Library\StringHelper::toUTF8($file, $encoding);
            } else {
                MailLog::info('File encoding is UTF-8');
                \Acelle\Library\StringHelper::checkAndRemoveUTF8BOM($file);
            }

            // Read CSV files
            $reader = \League\Csv\Reader::createFromPath($file);
            $reader->setHeaderOffset(0);
            // get the headers, using array_filter to strip empty/null header
            // to avoid the error of "InvalidArgumentException: Use a flat array with unique string values in /home/nghi/mailixa/vendor/league/csv/src/Reader.php:305"
            $headers = array_filter(array_map(function ($value) {
                return strtolower(trim($value));
            }, $reader->getHeader()));

            // custom fields of the list
            $fields = collect($this->fields)->map(function ($field) {
                return strtolower($field->tag);
            })->toArray();

            // list's fields found in the input CSV
            $availableFields = array_intersect($headers, $fields);

            // Special fields go here
            if (!in_array('tags', $availableFields)) {
                $availableFields[] = 'tags';
            }
            // ==> email, first_name, last_name, tags

            // split the entire list into smaller batches
            $results = $reader->getRecords($headers);

            return [$headers, $availableFields, iterator_count($results), $results];
        } catch (\Exception $ex) {
            // @todo: translation here
            throw new \Exception('Invalid headers. Original error message is: '.$ex->getMessage());
        }
    }

    /**
     * Validate imported file's headers.
     *
     * @param headers
     *
     * @return true or throw an exception
     */
    private function validateCsvHeader($headers)
    {
        // @todo: validation rules required here, currently hard-coded
        $missing = array_diff(['email'], $headers);
        if (!empty($missing)) {
            // @todo: I18n is required here
            throw new \Exception(trans('messages.import_missing_header_field', ['fields' => implode(', ', $missing)]));
        }

        return true;
    }

    /**
     * Validate imported record.
     *
     * @param headers
     *
     * @return bool whether or not the record is valid
     */
    private function validateCsvRecord($record)
    {
        //@todo: failed validate should affect the count showing up on the UI (currently, failed is also counted as success)
        $validator = Validator::make(
            $record,
            Subscriber::$rules,
            ['email' => 'invalid email address']
        );

        return [$validator->passes(), $validator->errors()->all()];
    }

    /**
     * Import subscriber from a CSV file.
     *
     * @param string original value
     *
     * @return string quoted value
     * @todo: use MySQL escape function to correctly escape string with astrophe
     */
    public function import($file, $progressCallback = null, $invalidRecordCallback = null)
    {
        $processed = 0;
        $failed = 0;
        $total = 0;
        $message = null;

        $overQuotaAttempt = false;

        if (!is_null($progressCallback)) {
            $progressCallback($processed, $total, $failed, $message = 'Import is starting...');
        }

        // Read CSV files
        list($headers, $availableFields, $total, $results) = $this->readCsv($file);

        if (!is_null($progressCallback)) {
            $progressCallback($processed, $total, $failed, $message = 'Validating file headers...');
        }

        // validate headers, check for required fields
        // throw an exception in case of error
        try {
            $this->validateCsvHeader($availableFields);
        } catch (Exception $ex) {
            // Generate a prettier error message which is logged and shown up to admin
            throw new Exception(sprintf('Import job dispatched by user "%s" failed. Error: %s', $this->customer->user->email, $ex->getMessage()));
        }

        if (!is_null($progressCallback)) {
            $progressCallback($processed, $total, $failed, $message = 'Loading records from file...');
        }

        try {
            // update status, line count

            // process by batches
            each_batch($results, config('app.import_batch_size'), false, function ($batch) use ($availableFields, &$processed, &$failed, $total, &$overQuotaAttempt, &$message, $progressCallback, $invalidRecordCallback) {

                // authorization
                if ($this->customer->user->can('addMoreSubscribers', [$this, sizeof($batch)])) {
                    $insertLimit = sizeof($batch);
                } else {
                    $insertLimit = $this->getRemainingAddSubscribersQuota();
                }

                $insertLimit = ($insertLimit < 0) ? 0 : $insertLimit;

                // if ($insertLimit == 0) { // only == 0 is enough
                //     throw new \Exception(trans('messages.import.notice.over_quota'));
                // }

                // create a temporary table containing the input subscribers
                $tmpTable = table('__tmp_subscribers');
                // @todo: hard-coded charset and COLLATE
                $tmpFields = implode(',', array_map(function ($field) {
                    return "`{$field}` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
                }, $availableFields));

                // Drop table, create table and create index
                DB::statement("DROP TABLE IF EXISTS {$tmpTable};");
                DB::statement("CREATE TABLE {$tmpTable}({$tmpFields}) ENGINE=InnoDB;");
                DB::statement("CREATE INDEX _index_email_{$tmpTable} ON {$tmpTable}(`email`);");

                // Insert subscriber fields from the batch to the temporary table
                // extract only fields whose name matches TAG NAME of MailList
                $data = collect($batch)->map(function ($r) use ($availableFields) {
                    $record = array_only($r, $availableFields);
                    if (!is_null($record['email'])) {
                        // replace the non-break space (not a normal space) as well as all other spaces
                        $record['email'] = strtolower(preg_replace('/[ \s*]*/', '', trim($record['email'])));
                    }

                    if (array_key_exists('tags', $record) && !empty($record['tags'])) {
                        $record['tags'] = json_encode(array_filter(preg_split('/\s*,\s*/', $record['tags'])));
                    }

                    // In certain cases, a UTF-8 BOM is added to email
                    // For example: "﻿madxperts@gmail.com" (open with Sublime to see the char)
                    // So we need to remove it, at least for email field
                    $record['email'] = StringHelper::removeUTF8BOM($record['email']);

                    return $record;
                })->toArray();

                // make the import data table unique by email
                $data = array_unique_by($data, function ($r) {
                    return $r['email'];
                });

                // validate amd remove invalid records
                $data = array_where($data, function ($record) use (&$failed, $invalidRecordCallback) {
                    list($valid, $errors) = $this->validateCsvRecord($record);
                    if (!$valid) {
                        $failed += 1;
                        if (!is_null($invalidRecordCallback)) {
                            $invalidRecordCallback($record, $errors);
                        }
                    }

                    return $valid;
                });

                // INSERT TO tmp TABLE
                DB::table('__tmp_subscribers')->insert($data);
                $newRecordCount = DB::select("SELECT COUNT(*) AS count FROM {$tmpTable} tmp LEFT JOIN ".table('subscribers')." main ON (tmp.email = main.email AND main.mail_list_id = {$this->id}) WHERE main.email IS NULL")[0]->count;

                if ($newRecordCount > 0 && $insertLimit == 0) {
                    // Only warning at this time
                    // when there is new records to INSERT but there is no more insert credit
                    // It is just fine if $newRecordCount == 0, then only update existing subscribers
                    // Just let it proceed until finishing
                    $overQuotaAttempt = true;
                }

                // processing for every batch,
                // using transaction to only commit at the end of the batch execution
                DB::beginTransaction();

                // Insert new subscribers from temp table to the main table
                // Use SUBSTRING(MD5(UUID()), 1, 13) to produce a UNIQUE ID which is similar to the output of PHP uniqid()
                // @TODO LIMITATION: tags are not updated if subscribers already exist
                DB::statement('INSERT INTO '.table('subscribers').'(uid, mail_list_id, email, status, subscription_type, tags, created_at, updated_at)
                               SELECT SUBSTRING(MD5(UUID()), 1, 13), ' .$this->id.', uniq.email, '.db_quote(Subscriber::STATUS_SUBSCRIBED).', '.db_quote(Subscriber::SUBSCRIPTION_TYPE_IMPORTED).", uniq.tags, NOW(), NOW()
                               FROM (SELECT tmp.email, tmp.tags FROM {$tmpTable} tmp LEFT JOIN ".table('subscribers')." main ON (tmp.email = main.email AND main.mail_list_id = {$this->id}) WHERE main.email IS NULL LIMIT {$insertLimit}) uniq");

                // Insert subscribers' custom fields to the fields table
                // OPTION 1: DELETE WHERE IN
                // DB::statement('DELETE FROM '.table('subscriber_fields').' WHERE subscriber_id IN (SELECT main.id FROM '.table('subscribers')." main JOIN {$tmpTable} tmp ON main.email = tmp.email WHERE mail_list_id = ".$this->id.')');

                // OPTION 2: DELETE JOIN
                DB::statement(sprintf(
                    '
                    DELETE f
                    FROM %s main
                    JOIN %s tmp ON main.email = tmp.email
                    JOIN %s f ON main.id = f.subscriber_id
                    WHERE mail_list_id = %s;',
                    table('subscribers'),
                    $tmpTable,
                    table('subscriber_fields'),
                    $this->id
                ));

                foreach ($availableFields as $field) {
                    $sql = 'INSERT INTO '.table('subscriber_fields')."(subscriber_id, field_id, value, created_at, updated_at)
                    SELECT t.subscriber_id, f.id, t.`{$field}`, NOW(), NOW()
                    FROM (SELECT main.id AS subscriber_id, tmp.{$field} FROM ".table('subscribers')." main JOIN {$tmpTable} tmp ON tmp.email = main.email WHERE main.mail_list_id = ".$this->id.') t
                    JOIN ' .table('fields')." f ON f.tag = '{$field}' AND f.mail_list_id = ".$this->id;
                    DB::statement($sql);
                }

                // update status, finish one batch
                $processed += sizeof($batch);
                if (!is_null($progressCallback)) {
                    $progressCallback($processed, $total, $failed, $message = 'Inserting contacts to database...');
                }

                // Actually write to the database
                DB::commit();

                // Cleanup
                DB::statement("DROP TABLE IF EXISTS {$tmpTable};");

                // Trigger updating related campaigns cache
                $this->updateCachedInfo();

                // blacklist new emails (if any)
                Blacklist::doBlacklist($this->customer);

                // Trigger
                MailListImported::dispatch($this);
            });

            if (!is_null($progressCallback)) {
                $progressCallback($processed, $total, $failed, $message = "Processed: {$processed}/{$total} records, skipped: {$failed} records.");
            }
        } catch (\Throwable $e) {
            // IMPORTANT: rollback first before throwing, otherwise it will be a deadlock
            DB::rollBack();

            if (!is_null($progressCallback)) {
                $progressCallback($processed, $total, $failed, $message = $e->getMessage());
            }

            // Is is weird that, in certain case, the $e->getMessage() string is too long, making the job "hang";
            throw new Exception(substr($e->getMessage(), 0, 512));
        } finally {
            // @IMPORTANT: if process fails here, something weird occurs
            $this->reformatDateFields();
            $this->updateCachedInfo();

            if (!is_null($progressCallback)) {
                $progressCallback($processed, $total, $failed, $message);
            }
        }
    }

    // Call by the LoadImportJobs JOB
    public function parseCsvFile($file, $callback)
    {
        $processed = 0;
        $failed = 0;
        $total = 0;
        $message = null;

        // Read CSV files
        list($headers, $availableFields, $total, $results) = $this->readCsv($file);

        // validate headers, check for required fields
        // throw an exception in case of error
        $this->validateCsvHeader($availableFields);

        // update status, line count

        // process by batches
        each_batch($results, $batchSize = 100, false, function ($batch) use ($availableFields, $callback) {
            $data = collect($batch)->map(function ($r) use ($availableFields) {
                $record = array_only($r, $availableFields);
                if (!is_null($record['email'])) {
                    // replace the non-break space (not a normal space) as well as all other spaces
                    $record['email'] = strtolower(preg_replace('/[ \s*]*/', '', trim($record['email'])));
                }

                if (array_key_exists('tags', $record) && !empty($record['tags'])) {
                    $record['tags'] = json_encode(array_filter(preg_split('/\s*,\s*/', $record['tags'])));
                }

                return $record;
            })->toArray();

            // make the import data table unique by email
            $data = array_unique_by($data, function ($r) {
                return $r['email'];
            });

            foreach ($data as $record) {
                $callback($record);
            }
        });
    }

    public function addSubscriberFromArray($attributes)
    {
        list($valid, $errors) = $this->validateCsvRecord($attributes);
        if (!$valid) {
            throw new Exception("Invalid record: ".implode(";", $errors));
        }

        // Create or update the subscriber record
        $subscriber = $this->subscribers()->where('email', $attributes['email'])->firstOrNew();
        $subscriber->fill($attributes);
        $subscriber->status = Subscriber::STATUS_SUBSCRIBED;
        $subscriber->save();

        // Create or update subscriber fields
        $subscriber->updateFields2($attributes);

        return $subscriber;
    }

    /**
     * Update List related cache.
     */
    public function updateCachedInfo()
    {
        // Update list's cached information
        $this->updateCache();

        // Update segments cached information
        foreach ($this->segments as $segment) {
            $segment->updateCache();
        }

        // Update user's cached information
        $this->customer->updateCache();
    }

    public function mailListsSendingServers()
    {
        return $this->hasMany('Acelle\Model\MailListsSendingServer');
    }

    public function activeMailListsSendingServers()
    {
        return $this->mailListsSendingServers()
            ->join('sending_servers', 'sending_servers.id', '=', 'mail_lists_sending_servers.sending_server_id')
            ->where('sending_servers.status', '=', SendingServer::STATUS_ACTIVE);
    }

    /**
     * Update sending servers.
     *
     * @return array
     */
    public function updateSendingServers($servers)
    {
        $this->mailListsSendingServers()->delete();
        foreach ($servers as $key => $param) {
            if ($param['check']) {
                $server = SendingServer::findByUid($key);
                $row = new MailListsSendingServer();
                $row->mail_list_id = $this->id;
                $row->sending_server_id = $server->id;
                $row->fitness = $param['fitness'];
                $row->save();
            }
        }
    }

    /**
     * Update Campaign cached data.
     */
    public function updateCache($key = null)
    {
        // cache indexes
        $index = [
            // @note: SubscriberCount must come first as its value shall be used by the others
            'SubscriberCount' => function (&$list) {
                return $list->subscribersCount(false);
            },
            'VerifiedSubscriberCount' => function (&$list) {
                return $list->subscribers()->verified()->count();
            },
            'ClickedRate' => function (&$list) {
                return $list->clickRate();
            },
            'UniqOpenRate' => function (&$list) {
                return $list->openUniqRate();
            },
            'SubscribeRate' => function (&$list) {
                return $list->subscribeRate(true);
            },
            'SubscribeCount' => function (&$list) {
                return $list->subscribeCount();
            },
            'UnsubscribeRate' => function (&$list) {
                return $list->unsubscribeRate(true);
            },
            'UnsubscribeCount' => function (&$list) {
                return $list->unsubscribeCount();
            },
            'UnconfirmedCount' => function (&$list) {
                return $list->unconfirmedCount();
            },
            'BlacklistedCount' => function (&$list) {
                return $list->blacklistedCount();
            },
            'SpamReportedCount' => function (&$list) {
                return $list->spamReportedCount();
            },
            'SegmentSelectOptions' => function (&$list) {
                return $list->getSegmentSelectOptions(true);
            },
            'LongName' => function (&$list) {
                return $list->longName(true);
            },
            'VerifiedSubscribersPercentage' => function (&$list) {
                return $list->getVerifiedSubscribersPercentage(true);
            },

        ];

        // retrieve cached data
        $cache = json_decode($this->cache, true);
        if (is_null($cache)) {
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

    /**
     * Send mails of list.
     *
     * @param Subscriber $subscriber
     * @param Page       $page
     * @param string     $title
     *
     * @var void
     */
    public function sendMail($subscriber, $page, $title)
    {
        $page->renderContent(null, $subscriber);

        $body = view('pages._email_content', ['page' => $page])->render();

        // Create a message
        $message = new ExtendedSwiftMessage($title);
        $message->setFrom(array($subscriber->mailList->from_email => $subscriber->mailList->from_name));
        $message->setTo(array($subscriber->email, $subscriber->email => $subscriber->getFullName(trans('messages.to_email_name'))));
        $message->addPart($body, 'text/html');

        try {
            $this->send($message, [
                'subscriber' => $subscriber,
            ]);
        } catch (\Exception $ex) {
            $error = $ex->getMessage();
            MailLog::error($error);
            throw new \Exception($error);
        }
    }

    public function getCurrentSubscription()
    {
        if (empty($this->currentSubscription)) {
            $this->currentSubscription = $this->customer->activeSubscription();
        }

        return $this->currentSubscription;
    }

    /**
     * Pick one sending server associated to the Mail List.
     *
     * @return object SendingServer
     */
    public function pickSendingServer()
    {
        $selection = $this->getSendingServers();

        // raise an exception if no sending servers are available
        if (empty($selection)) {
            throw new \Exception(sprintf('No sending server available for Mail List ID %s', $this->id));
        }

        // do not raise an exception, just wait if sending servers are available but exceeding sending limit
        $blacklisted = [];

        while (true) {
            $id = RouletteWheel::take($selection);
            if (empty(self::$serverPools[$id])) {
                $server = SendingServer::find($id);
                MailLog::info(sprintf('Initialize delivery server `%s` (ID: %s)', $server->name, $id));

                $server = SendingServer::mapServerType($server);

                // flag the server to use sub-account instead
                $subscription = $this->getCurrentSubscription();
                if (!is_null($subscription->sub_account_id)) {
                    $server->setSubAccount($subscription->subAccount);
                }
                self::$serverPools[$id] = $server;
            }

            MailLog::info(sprintf('Pick up delivery server `%s` (ID: %s)', self::$serverPools[$id]->name, $id));

            return self::$serverPools[$id];
        }
    }

    /**
     * Check if list can send through it's sending servers.
     *
     * @var bool
     */
    public function getSendingServers()
    {
        if (!is_null($this->sendingSevers)) {
            return $this->sendingSevers;
        }

        $result = [];
        $subscription = $this->getCurrentSubscription();

        // Check the customer has permissions using sending servers and has his own sending servers
        if ($this->customer->getOption('sending_server_option') == \Acelle\Model\Plan::SENDING_SERVER_OPTION_OWN) {
            if ($this->all_sending_servers) {
                if ($this->customer->activeSendingServers()->count()) {
                    $result = $this->customer->activeSendingServers()->get()->map(function ($server) {
                        return [$server->id, '100'];
                    });
                }
            } elseif ($this->activeMailListsSendingServers()->count()) {
                $result = $this->activeMailListsSendingServers()->get()->map(function ($server) {
                    return [$server->sending_server_id, $server->fitness];
                });
            }
            // If customer dont have permission creating sending servers
        } elseif ($this->customer->getOption('sending_server_option') == \Acelle\Model\Plan::SENDING_SERVER_OPTION_SYSTEM) {
            // Check if has sending servers for current subscription
            if (is_object($subscription)) {
                /*
                if ($subscription->plan->getOption('all_sending_servers') == 'yes') {
                    if (\Acelle\Model\SendingServer::system()->count()) {
                        $result = \Acelle\Model\SendingServer::system()->get()->map(function ($server) {
                            return [$server->id, '100'];
                        });
                    }
                } elseif ($subscription->activeSubscriptionsSendingServers()->count()) {
                    $result = $subscription->activeSubscriptionsSendingServers()->get()->map(function ($server) {
                        return [$server->sending_server_id, $server->fitness];
                    });
                }
                */

                $result = $subscription->plan->activeSendingServers->map(function ($server) {
                    return [$server->sending_server_id, $server->fitness];
                });
            }
            //} elseif ($subscription->useSubAccount()) {
        //    $result[] = [$subscription->subAccount->sending_server_id, '100'];
        }

        if (!config('app.saas')) {
            $result = \Acelle\Model\SendingServer::active()->get()->map(function ($server) {
                return [$server->id, 100];
            });
        }

        $assoc = [];
        foreach ($result as $server) {
            list($key, $fitness) = $server;
            $assoc[(int) $key] = $fitness;
        }

        $this->sendingSevers = $assoc;

        return $this->sendingSevers;
    }

    /**
     * Reset verification data for list.
     */
    public function resetVerification()
    {
        DB::statement(sprintf('UPDATE %s s SET verification_status = NULL, last_verification_by = NULL, last_verification_at = NULL, last_verification_result = NULL WHERE s.mail_list_id = %s', table('subscribers'), $this->id));
    }

    /**
     * get verified subscribers percentage.
     */
    public function getVerifiedSubscribersPercentage($cache = false)
    {
        $count = $this->subscribersCount($cache);
        if ($count == 0) {
            return 0.0;
        } else {
            return (float) $this->subscribers()->verified()->count() / $count;
        }
    }

    /**
     * Subscribers count.
     */
    public function subscribersCount($cache = false)
    {
        if ($cache) {
            return $this->readCache('SubscriberCount', 0);
        }

        return $this->subscribers()->count();
    }

    /**
     * Segments count.
     */
    public function segmentsCount()
    {
        return $this->segments()->count();
    }

    /**
     * Copy new list.
     */
    public function copyAll($name, $customer = null)
    {
        $copy = $this->replicate(['cache']);
        $copy->name = $name;
        $copy->created_at = \Carbon\Carbon::now();
        $copy->updated_at = \Carbon\Carbon::now();

        if ($customer) {
            $copy->customer_id = $customer->id;
        }

        $copy->save();

        // Contact
        if (is_object($this->contact)) {
            $new_contact = $this->contact->replicate();
            $new_contact->save();

            // update contact
            $copy->contact_id = $new_contact->id;
            $copy->save();
        }

        // Remove default fields
        $copy->fields()->delete();
        // Fields
        foreach ($this->fields as $field) {
            $new_field = $field->replicate();
            $new_field->mail_list_id = $copy->id;
            $new_field->save();

            // Copy field options
            foreach ($field->fieldOptions as $option) {
                $new_option = $option->replicate();
                $new_option->field_id = $new_field->id;
                $new_option->save();
            }
        }

        // copy all subscribers
        foreach ($this->subscribers as $subscriber) {
            $subscriber->copy($copy);
        }

        // update cache
        $copy->updateCache();
    }

    /**
     * Segments count.
     */
    public function cloneForCustomers($customers)
    {
        foreach ($customers as $customer) {
            $this->copyAll($this->name, $customer);
        }
    }

    public function subscribe($request, $source)
    {
        // Validation
        // It is ok to have subscriber subscribe again without any confusing message
        //     if (is_object($subscriber) && $subscriber->status == \Acelle\Model\Subscriber::STATUS_SUBSCRIBED) {
        //        $rules['email_already_subscribed'] = 'required';
        //     }

        $messages = [];
        foreach ($this->getFields as $field) {
            if ($field->tag == 'EMAIL') {
                $messages[$field->tag . '.required' ] = trans('messages.list.validation.required', ['field' => $field->label]);
                $messages[$field->tag . '.email' ] = trans('messages.list.validation.not_email', ['field' => $field->label]);
            } elseif ($field->required) {
                $messages[$field->tag . '.required' ] = trans('messages.list.validation.required', ['field' => $field->label]);
            }
        }

        // List rules
        $rules = $this->getFieldRules();

        // Do not allow duplicate email if added by admin (throw an exception)
        // If imported / api / web / embedded-form THEN just overwrite
        if ($source == Subscriber::SUBSCRIPTION_TYPE_ADDED || $source == self::SOURCE_API) {
            // @important
            // DO NOT USE "UNIQUE" validator of Laravel
            // Otherwise, it will fails after the subscriber with given email address is added
            $rules['EMAIL'] = [
                'required',
                'email',
                Rule::unique('subscribers')->where(function ($query) {
                    return $query->where('mail_list_id', $this->id);
                }),
            ];
        }

        $validator = \Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return [$validator, null];
        }

        // Validated, proceed
        $subscriber = $this->subscribers()->firstOrNew(['email' => strtolower(trim($request->EMAIL))]);
        $subscriber->from = $source;

        // Check if subscriber already in blacklist
        if ($subscriber->isListedInBlacklist()) {
            throw new \Exception(trans('messages.subscriber.blacklisted'));
        }

        if ($source == Subscriber::SUBSCRIPTION_TYPE_ADDED || $source == self::SOURCE_API) {
            $subscriber->status = 'subscribed';
        } elseif ($this->subscribe_confirmation) {
            if ($subscriber->isSubscribed()) {
                MailLog::info("Subscriber {$subscriber->email} already in the list");
            } else {
                $subscriber->status = 'unconfirmed';
            }
        } else {
            $subscriber->status = 'subscribed';
        }
        $subscriber->ip = $request->ip();
        $subscriber->save();

        // @IMPORTANT
        // After the $subscriber->save(), $validator->fails() becomes TRUE!!!!
        // Because the email address is now not available
        // This is a problem of Laravel

        $subscriber->updateFields($request->all());

        // update MailList cache
        MailListUpdated::dispatch($this);

        if ($subscriber->isSubscribed()) {
            // Only trigger MailListSubscription event if subscribe is immediately subscribed
            MailListSubscription::dispatch($subscriber);
        }

        MailLog::info("Subscriber {$subscriber->email} is added to list {$this->name} via {$source}");

        if ($this->subscribe_confirmation && !$subscriber->isSubscribed() && $source != Subscriber::SUBSCRIPTION_TYPE_ADDED) {
            MailLog::info("Send subscription confirmation email to {$subscriber->email}, list {$this->name}");
            $this->sendSubscriptionConfirmationEmail($subscriber);
        }

        return [$validator, $subscriber];
    }

    public function reformatDateFields($subscriber_id = null)
    {
        $type = 'date';

        $query = $this->subscriberFields()
                      ->where('fields.type', $type)
                      ->select('subscriber_fields.id', 'subscriber_fields.value');

        if (!is_null($subscriber_id)) {
            $query = $query->where('subscriber_id', $subscriber_id);
        }

        $query->perPage($pageSize = 1000, function ($batch) {
            $fixedValues = $batch->get()->map(function ($r) {
                return [
                    'id' => $r->id,
                    'value' => $this->customer->parseDateTime($r->value, true)->format(config('custom.date_format')),
                ];
            })->toArray();

            $this->createTemporaryTableFromArray(
                "_tmp_{$this->customer->uid}_date_values",
                $fixedValues,
                [
                    'id BIGINT',
                    'value VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci'
                ],
                function ($table) {
                    DB::statement(sprintf("UPDATE %s sf INNER JOIN %s t ON sf.id = t.id SET sf.value = t.value", table('subscriber_fields'), table($table)));
                }
            );
        });

        /****** The following method is not working

        $sql = "DROP TABLE IF EXISTS __tmp__;
            CREATE TABLE __tmp__ AS
            SELECT sf.id, DATE_FORMAT(COALESCE(
                STR_TO_DATE(sf.value,'{$normalizedDateFormat}'),
                STR_TO_DATE(sf.value,'%Y:%m:%d'),
                STR_TO_DATE(sf.value,'%Y.%m.%d'),
                STR_TO_DATE(sf.value,'%Y/%m/%d')
            ), '{$normalizedDateFormat}') as pretty_date
            FROM ".table('subscriber_fields')." sf
            INNER JOIN ".table('fields')." f ON sf.field_id = f.id
            WHERE f.`type` = 'date'
            AND DATE_FORMAT(STR_TO_DATE(sf.value,'{$normalizedDateFormat}'), '{$normalizedDateFormat}') != sf.value
            AND f.mail_list_id = {$this->id};

            UPDATE ".table('subscriber_fields')." sf
            INNER JOIN __tmp__ tmp ON sf.id = tmp.id
            SET value = tmp.pretty_date
            WHERE value <> tmp.pretty_date";
        DB::statement($sql);
        **********/
    }

    public function verificationJobs()
    {
        return $this->jobMonitors()->orderBy('job_monitors.id', 'DESC')->where('job_type', VerifyMailListJob::class);
    }

    public function importJobs()
    {
        return $this->jobMonitors()->orderBy('job_monitors.id', 'DESC')
                                   ->whereIn('job_type', [ ImportSubscribersJob::class, ImportSubscribers2::class ]);
    }

    public function exportJobs()
    {
        return $this->jobMonitors()->orderBy('job_monitors.id', 'DESC')->where('job_type', ExportSubscribersJob::class);
    }

    // Strategy pattern here
    public function getProgress($job)
    {
        if ($job->hasBatch()) {
            $progress = $job->getJsonData();
            $progress['status'] = $job->status;
            $progress['error'] = $job->error;
            $progress['percentage'] = $job->getBatch()->progress();
            $progress['total'] = $job->getBatch()->totalJobs;
            $progress['processed'] = $job->getBatch()->processedJobs();
            $progress['failed'] = $job->getBatch()->failedJobs;
        } else {
            $progress = $job->getJsonData();
            $progress['status'] = $job->status;
            $progress['error'] = $job->error;
            // The following attributes are already availble
            // $progress['percentage']
            // $progress['total']
            // $progress['processed']
            // $progress['failed']
        }

        return $progress;
    }

    public function updateOrCreateFieldsFromRequest($request)
    {
        $rules = [];

        // Check if filed does not have EMAIL tag
        $conflict_tag = false;
        $tags = [];
        foreach ($request->fields as $key => $item) {
            // If email field
            if ($this->getEmailField()->uid != $item['uid']) {
                // check required input
                $rules['fields.'.$key.'.label'] = 'required';
                $rules['fields.'.$key.'.tag'] = 'required|alpha_dash';

                // check field options
                if (isset($item['options'])) {
                    foreach ($item['options'] as $key2 => $item2) {
                        $rules['fields.'.$key.'.options.'.$key2.'.label'] = 'required';
                        $rules['fields.'.$key.'.options.'.$key2.'.value'] = 'required';
                    }
                }

                // Check tag exsit
                $tag = \Acelle\Model\Field::formatTag($item['tag']);
                if (in_array($tag, $tags)) {
                    $conflict_tag = true;
                }
                $tags[] = $tag;
            }
        }
        if ($conflict_tag) {
            $rules['conflict_field_tags'] = 'required';
        }

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $validator;
        }

        // Save fields
        $saved_ids = [];
        foreach ($request->fields as $uid => $item) {
            $field = \Acelle\Model\Field::findByUid($item['uid']);
            if (!is_object($field)) {
                $field = new \Acelle\Model\Field();
                $field->mail_list_id = $this->id;
            }

            // If email field
            if ($this->getEmailField()->uid != $field->uid) {
                // save exsit field
                $item['tag'] = \Acelle\Model\Field::formatTag($item['tag']);
                $field->fill($item);
                $field->save();

                // save field options
                $field->fieldOptions()->delete();
                if (isset($item['options'])) {
                    foreach ($item['options'] as $key2 => $item2) {
                        $option = new \Acelle\Model\FieldOption($item2);
                        $option->field_id = $field->id;
                        $option->save();
                    }
                }
            } else {
                $field->label = $item['label'];
                $field->default_value = $item['default_value'];
                $field->save();
            }

            // store save ids
            $saved_ids[] = $field->uid;
        }

        // Delete fields
        foreach ($this->getFields as $field) {
            if (!in_array($field->uid, $saved_ids) && $field->uid != $this->getEmailField()->uid) {
                $field->delete();
            }
        }

        return $validator;
    }

    public function getFieldsFromParams($params)
    {
        $fields = collect();
        // Get old post values
        if (isset($params['fields'])) {
            foreach ($params['fields'] as $key => $item) {
                $field = \Acelle\Model\Field::findByUid($item['uid']);
                if (!is_object($field)) {
                    $field = new \Acelle\Model\Field();
                    $field->uid = $key;
                }
                $field->fill($item);

                // If email field
                if ($this->getEmailField()->uid == $field->uid) {
                    $field = $this->getEmailField();
                    $field->label = $item['label'];
                    $field->default_value = $item['default_value'];
                }

                // Field options
                if (isset($item['options'])) {
                    $field->fieldOptions = collect();
                    foreach ($item['options'] as $key2 => $item2) {
                        $option = new \Acelle\Model\FieldOption($item2);
                        $option->uid = $key2;
                        $field->fieldOptions->push($option);
                    }
                }

                $fields[] = $field;
            }
        }

        return $fields;
    }

    public function defaultEmbeddedFormOptions()
    {
        return [
            'form_title' => trans('messages.Subscribe_to_our_mailing_list'),
            'redirect_url' => '',
            'only_required_fields' => 'no',
            'stylesheet' => 'yes',
            'javascript' => 'yes',
            'show_invisible' => 'no',
            'custom_css' => '.subscribe-embedded-form {
    color: #333
}
.subscribe-embedded-form label {
    color: #555
}',
        ];
    }

    public function getEmbeddedFormOptions()
    {
        if ($this->embedded_form_options == null) {
            return [];
        }

        return json_decode($this->embedded_form_options, true);
    }

    public function getEmbeddedFormOption($key)
    {
        $defaults = $this->defaultEmbeddedFormOptions();
        $options = $this->getEmbeddedFormOptions();

        if (isset($options[$key])) {
            return $options[$key];
        } else {
            return $defaults[$key];
        }
    }

    public function setEmbeddedFormOptions($params)
    {
        $this->embedded_form_options = json_encode($params);
        $this->save();
    }
}
