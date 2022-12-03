<?php

/**
 * BounceHandler class.
 *
 * Model class for email bounces handling
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

use Acelle\Library\Traits\HasUid;

class BounceHandler extends DeliveryHandler
{
    use HasUid;

    protected $table = 'bounce_handlers';

    public static $itemsPerPage = 25;

    /**
     * Process bounce message, extract the bounce information.
     *
     * @return mixed
     */
    public function processMessage($mbox, $msgNo)
    {
        try {
            $header = imap_headerinfo($mbox, $msgNo);
            $body = imap_body($mbox, $msgNo, FT_PEEK);

            /* The following check is now deprecated
             * and will be removed
                 $bouncedAddress = $this->getBouncedAddress($header->toaddress);
                 print_r($bouncedAddress . "\n");
                 if (empty($bouncedAddress)) {
                     throw new \Exception("not a bounce message");
                 }
            */

            $msgId = $this->getMessageId($body);
            if (empty($msgId)) {
                imap_setflag_full($mbox, $msgNo, '\\Seen \\Flagged');
                $this->logger()->info('Skipped: cannot find Message-ID in email body');
                return;
            } else {
                $this->logger()->info('Parsed OK, Message-ID found in email body, proceeding with '.$msgId);
            }

            $trackingLog = TrackingLog::where('message_id', $msgId)->first();

            if (empty($trackingLog)) {
                $this->logger()->info('Skipped: cannot find message with such Message-Id: '.$msgId);
                return;
            }

            // record a bounce log, one message may have more than one
            $bounceLog = new BounceLog();
            $bounceLog->message_id = $msgId;
            $bounceLog->runtime_message_id = $msgId;
            $bounceLog->bounce_type = 'unknown'; // @TODO fill in the NULL value here
            $bounceLog->raw = imap_fetchheader($mbox, $msgNo).PHP_EOL.$body;
            $bounceLog->save();

            // just delete the bounce notification email
            imap_delete($mbox, $msgNo);

            $this->logger()->info('Done: bounce recorded for message '.$msgId);
            $this->logger()->info('Adding email to blacklist...');
            $trackingLog->subscriber->sendToBlacklist($bounceLog->raw);
            $this->logger()->info('Added');
        } catch (\Exception $ex) {
            $this->logger()->info('Failed. '.$ex->getMessage());
        }
    }

    /**
     * Extract bounced email address from email.
     *
     * @return string emailAddress
     */
    public function getBouncedAddress($to)
    {
        preg_match('/(?<=\+)[^\+]+=[^@]+(?=@)/', $to, $matched);
        if (sizeof($matched) == 0) {
            return;
        } else {
            return str_replace('=', '@', $matched[0]);
        }
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

    /**
     * Filter items.
     *
     * @return collect
     */
    public static function filter($request)
    {
        $user = $request->user();
        $admin = $user->admin;
        $query = self::select('bounce_handlers.*');

        // Keyword
        if (!empty(trim($request->keyword))) {
            foreach (explode(' ', trim($request->keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('bounce_handlers.name', 'like', '%'.$keyword.'%')
                        ->orWhere('bounce_handlers.type', 'like', '%'.$keyword.'%')
                        ->orWhere('bounce_handlers.host', 'like', '%'.$keyword.'%');
                });
            }
        }

        // filters
        $filters = $request->all();
        if (!empty($filters)) {
            if (!empty($filters['type'])) {
                $query = $query->where('bounce_handlers.type', '=', $filters['type']);
            }
        }

        if (!empty($request->admin_id)) {
            $query = $query->where('bounce_handlers.admin_id', '=', $request->admin_id);
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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'host', 'port', 'username', 'password', 'protocol', 'encryption', 'email',
    ];

    /**
     * Get validation rules.
     *
     * @return object
     */
    public static function rules()
    {
        return [
            'name' => 'required',
            'host' => 'required',
            'port' => 'required',
            'username' => 'required',
            'password' => 'required',
            'protocol' => 'required',
            'encryption' => 'required',
            'email' => 'required|email',
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

        $options = $query->orderBy('name', 'asc')->get()->map(function ($item) {
            return ['value' => $item->id, 'text' => $item->name];
        });

        return $options;
    }

    /**
     * Protocol select options.
     *
     * @return array
     */
    public static function protocolSelectOptions()
    {
        return [
            ['value' => 'imap', 'text' => 'imap'],
        ];
    }

    /**
     * Encryption select options.
     *
     * @return array
     */
    public static function encryptionSelectOptions()
    {
        return [
            ['value' => 'tls', 'text' => 'tls'],
            ['value' => 'starttls', 'text' => 'starttls'],
            ['value' => 'notls', 'text' => 'notls'],
            ['value' => 'ssl', 'text' => 'ssl'],
        ];
    }
}
