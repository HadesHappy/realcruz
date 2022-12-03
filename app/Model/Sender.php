<?php

/**
 * Sender class.
 *
 * Model class for countries
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
use Illuminate\Support\Facades\Log as LaravelLog;
use Acelle\Library\ExtendedSwiftMessage;
use Acelle\Model\Setting;
use Acelle\Library\Traits\HasUid;
use App;

class Sender extends Model
{
    use HasUid;

    // Statuses
    public const STATUS_NEW = 'new'; // deprecated
    public const STATUS_PENDING = 'pending';
    public const STATUS_VERIFIED = 'verified';

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
        $query = $user->customer->senders();

        // Keyword
        if (!empty(trim($request->keyword))) {
            foreach (explode(' ', trim($request->keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('senders.email', 'like', '%'.$keyword.'%')
                        ->orwhere('senders.name', 'like', '%'.$keyword.'%');
                });
            }
        }

        // Other filter
        if (!empty($request->customer_id)) {
            $query = $query->where('senders.customer_id', '=', $request->customer_id);
        }

        return $query;
    }

    public function customer()
    {
        return $this->belongsTo('Acelle\Model\Customer');
    }

    public function sendingServer()
    {
        return $this->belongsTo('Acelle\Model\SendingServer');
    }

    public static function pending()
    {
        return self::where('status', self::STATUS_PENDING);
    }

    public function scopeVerified($query)
    {
        return $query->where('status', self::STATUS_VERIFIED);
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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email'
    ];

    /**
     * The rules for validation.
     *
     * @var array
     */
    public function rules()
    {
        $rules = array(
            'email' => 'required|email|unique:senders,email,'.$this->id.',id',
            'name' => 'required',
        );

        return $rules;
    }

    /**
     * The rules for validation.
     *
     * @var array
     */
    public function editRules()
    {
        $rules = array(
            'name' => 'required',
        );

        return $rules;
    }

    /**
     * Set sender status to pending.
     *
     * @var void
     */
    public function setPending()
    {
        $this->status = self::STATUS_PENDING;
        $this->save();
    }

    /**
     * Check if sender is verified.
     *
     * @return object
     */
    public function isVerified()
    {
        return $this->status == self::STATUS_VERIFIED;
    }

    /**
     * Get domain name from email.
     *
     * @return string
     */
    public function getDomain()
    {
        return explode('@', $this->email)[1];
    }

    /**
     * Get domain name from email.
     *
     * @return string
     */
    public static function getAllVerified()
    {
        return self::where('status', '=', self::STATUS_VERIFIED);
    }

    /**
     * Verify sender.
     *
     * @return string
     */
    public function updateVerificationStatus()
    {
        // only work for server of allowVerifyingOwnEmailsRemotely() == true
        $server = $this->sendingServer;
        if (!is_null($server)) {
            $verified = $server->mapType()->verifyIdentity($this->email);
            $this->status = $verified ? self::STATUS_VERIFIED : self::STATUS_PENDING;
            $this->save();
            LaravelLog::info(sprintf('Verify sender %s done, status: %s', $this->email, $this->status));
        } else {
            // verify by clicking on an Acelle link
            // so nothing to do here
        }
    }

    public function setVerified()
    {
        $this->status = self::STATUS_VERIFIED;
        $this->save();
        return $this;
    }

    public function toRFCEmailAddress()
    {
        return "{$this->name} <{$this->email}>";
    }

    public function isPending()
    {
        return $this->status == self::STATUS_PENDING;
    }

    public function verifyWith($server = null)
    {
        // Check if it is already verify
        $this->updateVerificationStatus();

        if ($this->isVerified()) {
            return;
        }

        if (!is_null($server) && $server->allowVerifyingOwnEmailsRemotely()) {
            // this sender is bound with a specific sending server
            // set $this->sending_server_id = $server->id the correct way
            $this->sendingServer()->associate($server);
            $this->setPending();
            $this->save();

            $server->mapType()->sendVerificationEmail($this);
        } else {
            // If server does not support verification
            $this->sendVerificationEmail();
        }
    }

    public function sendVerificationEmail()
    {
        $template = Layout::where('alias', 'sender_verification_email')->first();

        if (is_null($template)) {
            throw new \Exception("Layout/template 'sender_verification_email' is missing!");
        }

        $htmlContent = $template->content;

        $htmlContent = str_replace('{USER_NAME}', $this->name, $htmlContent);
        $htmlContent = str_replace('{USER_EMAIL}', $this->email, $htmlContent);
        $htmlContent = str_replace('{VERIFICATION_LINK}', $this->generateVerificationUrl(), $htmlContent);

        // build the message
        $message = new ExtendedSwiftMessage();
        $message->setEncoder(new \Swift_Mime_ContentEncoder_PlainContentEncoder('8bit'));
        $message->setContentType('text/html; charset=utf-8');

        $message->setSubject($template->subject);
        $message->setTo($this->email);
        $message->setReplyTo(Setting::get('mail.reply_to'));
        $message->addPart($htmlContent, 'text/html');

        $mailer = App::make('xmailer');
        $result = $mailer->sendWithDefaultFromAddress($message);

        if (array_key_exists('error', $result)) {
            throw new \Exception($result['error']);
        }
    }

    public function generateVerificationToken()
    {
        $token = urlencode(encrypt($this->uid));
        return $token;
    }

    public function generateVerificationUrl()
    {
        return action('SenderController@verify', [ 'token' => $this->generateVerificationToken() ]);
    }

    public function generateVerificationResultUrl()
    {
        return action('SenderController@verifyResult', [ 'uid' => $this->uid ]);
    }

    public static function verifyToken($token)
    {
        $uid = null;

        try {
            $uid = decrypt(urldecode($token));
        } catch (\Exception $ex) {
            throw new \Exception("Invalid sender verification token", 1);
        }

        $sender = self::findByUid($uid);

        if (is_null($sender)) {
            throw new \Exception("Identity not found", 1);
        }

        return $sender->setVerified();
    }
}
