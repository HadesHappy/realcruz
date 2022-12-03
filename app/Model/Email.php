<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use File;
use Validator;
use ZipArchive;
use KubAT\PhpSimple\HtmlDomParser;
use Acelle\Library\Tool;
use Acelle\Library\StringHelper;
use Acelle\Library\Log as MailLog;
use Acelle\Model\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Acelle\Jobs\SendMessage;
use Acelle\Library\Traits\HasTemplate;
use Acelle\Library\Traits\HasUid;
use Exception;

class Email extends Model
{
    use HasTemplate;
    use HasUid;

    // Email types
    public const TYPE_REGULAR = 'regular';
    public const TYPE_PLAIN_TEXT = 'plain-text';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subject', 'from_email', 'from_name', 'reply_to', 'sign_dkim', 'track_open', 'track_click', 'action_id',
    ];

    // Cached HTML content
    protected $parsedContent = null;

    /**
     * Association with mailList through mail_list_id column.
     */
    public function automation()
    {
        return $this->belongsTo('Acelle\Model\Automation2', 'automation2_id');
    }

    /**
     * Get the customer.
     */
    public function customer()
    {
        return $this->belongsTo('Acelle\Model\Customer');
    }

    /**
     * Association with attachments.
     */
    public function attachments()
    {
        return $this->hasMany('Acelle\Model\Attachment');
    }

    /**
     * Association with email links.
     */
    public function emailLinks()
    {
        return $this->hasMany('Acelle\Model\EmailLink');
    }

    /**
     * Association with open logs.
     */
    public function trackingLogs()
    {
        return $this->hasMany('Acelle\Model\TrackingLog');
    }

    public function emailWebhooks()
    {
        return $this->hasMany('Acelle\Model\EmailWebhook');
    }

    /**
     * Get email's associated tracking domain.
     */
    public function trackingDomain()
    {
        return $this->belongsTo('Acelle\Model\TrackingDomain', 'tracking_domain_id');
    }

    /**
     * Get email's default mail list.
     */
    public function defaultMailList()
    {
        return $this->automation->mailList();
    }

    /**
     * Create automation rules.
     *
     * @return array
     */
    public function rules($request=null)
    {
        $rules = [
            'subject' => 'required',
            'from_email' => 'required|email',
            'from_name' => 'required',
        ];

        // tracking domain
        if (isset($request) && $request->custom_tracking_domain) {
            $rules['tracking_domain_uid'] = 'required';
        }

        return $rules;
    }

    /**
     * Upload attachment.
     */
    public function uploadAttachment($file)
    {
        $file_name = $file->getClientOriginalName();
        $att = $this->attachments()->make();
        $att->size = $file->getSize();
        $att->name = $file->getClientOriginalName();

        $path = $file->move(
            $this->getAttachmentPath(),
            $att->name
        );

        $att->file = $this->getAttachmentPath($att->name);
        $att->save();

        return $att;
    }

    /**
     * Get attachment path.
     */
    public function getAttachmentPath($path = null)
    {
        return $this->customer->getAttachmentsPath($path);
    }

    /**
     * Find and update email links.
     */
    public function updateLinks()
    {
        if (!$this->getTemplateContent()) {
            return false;
        }

        $links = [];

        // find all links from contents
        // Fix: str_get_html returning false
        defined('MAX_FILE_SIZE') || define('MAX_FILE_SIZE', 10000000);
        $document = HtmlDomParser::str_get_html($this->getTemplateContent());
        foreach ($document->find('a') as $element) {
            if (preg_match('/^http/', $element->href) != 0) {
                $links[] = trim($element->href);
            }
        }

        // delete al bold links
        $this->emailLinks()->whereNotIn('link', $links)->delete();

        foreach ($links as $link) {
            $exist = $this->emailLinks()->where('link', '=', $link)->count();

            if (!$exist) {
                $this->emailLinks()->create([
                    'link' => $link,
                ]);
            }
        }
    }

    public function queueDeliverTo($subscriber, $triggerId = null)
    {
        $server = $subscriber->mailList->pickSendingServer();

        dispatch(new SendMessage(
            $this,
            $subscriber,
            $server,
            $triggerId
        ));
    }

    public function sendTestEmail($emailAddress)
    {
        $validator = Validator::make([ 'email' => $emailAddress ], [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            $firstErrorMsg = $validator->errors()->first();
            throw new Exception($firstErrorMsg);
        }

        $server = $this->automation->mailList->pickSendingServer();

        // Build a valid message with a fake contact
        // build a temporary subscriber oject used to pass through the sending methods
        $subscriber = $this->createStdClassSubscriber(['email' => $emailAddress]);
        list($message, $msgId) = $this->prepareEmail($subscriber, $server);
        $server->send($message);
    }

    /**
     * Log delivery message, used for later tracking.
     */
    public function trackMessage($response, $subscriber, $server, $msgId, $triggerId = null)
    {

        // @todo: customerneedcheck
        $params = array_merge(array(
            'email_id' => $this->id,
            'message_id' => $msgId,
            'subscriber_id' => $subscriber->id,
            'sending_server_id' => $server->id,
            'customer_id' => $this->automation->customer->id,
            'auto_trigger_id' => $triggerId,
        ), $response);

        if (!isset($params['runtime_message_id'])) {
            $params['runtime_message_id'] = $msgId;
        }

        // create tracking log for message
        $this->trackingLogs()->create($params);
    }

    public function isOpened($subscriber)
    {
        return $this->trackingLogs()->where('subscriber_id', $subscriber->id)
                            ->join('open_logs', 'open_logs.message_id', '=', 'tracking_logs.message_id')->exists();
    }

    public function isClicked($subscriber)
    {
        return $this->trackingLogs()->where('subscriber_id', $subscriber->id)
                            ->join('click_logs', 'click_logs.message_id', '=', 'tracking_logs.message_id')->exists();
    }

    /**
     * Fill email's fields from request.
     */
    public function fillAttributes($params)
    {
        $this->fill($params);

        // Tacking domain
        if (isset($params['custom_tracking_domain']) && $params['custom_tracking_domain'] && isset($params['tracking_domain_uid'])) {
            $tracking_domain = \Acelle\Model\TrackingDomain::findByUid($params['tracking_domain_uid']);
            if (is_object($tracking_domain)) {
                $this->tracking_domain_id = $tracking_domain->id;
            } else {
                $this->tracking_domain_id = null;
            }
        } else {
            $this->tracking_domain_id = null;
        }
    }

    public function isSetup()
    {
        return $this->subject && $this->reply_to && $this->from_email && $this->template;
    }

    public function deleteAndCleanup()
    {
        if ($this->template) {
            $this->template->deleteAndCleanup();
        }

        $this->delete();
    }

    public function logger()
    {
        return $this->automation->logger();
    }

    public function newWebhook()
    {
        $webhook = new \Acelle\Model\EmailWebhook();
        $webhook->email_id = $this->id;

        return $webhook;
    }
}
