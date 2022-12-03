<?php

/**
 * TrackingLog class.
 *
 * Model class for delivery log
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

class TrackingLog extends Model
{
    public const STATUS_SENT = 'sent';
    public const STATUS_FAILED = 'failed';

    public const STATUS_BOUNCED = 'bounced';
    public const STATUS_FEEDBACK_ABUSE = 'feedback-abuse';
    public const STATUS_FEEDBACK_SPAM = 'feedback-spam';

    protected $fillable = ['email_id', 'campaign_id', 'message_id', 'runtime_message_id', 'subscriber_id', 'sending_server_id', 'customer_id', 'status', 'error', 'auto_trigger_id', 'sub_account_id', 'mail_list_id'];

    /**
     * Associations.
     *
     * @var object | collect
     */
    public function customer()
    {
        return $this->belongsTo('Acelle\Model\Customer');
    }

    public function campaign()
    {
        return $this->belongsTo('Acelle\Model\Campaign');
    }

    public function mailList()
    {
        return $this->belongsTo('Acelle\Model\MailList');
    }

    public function sendingServer()
    {
        return $this->belongsTo('Acelle\Model\SendingServer');
    }

    public function subscriber()
    {
        return $this->belongsTo('Acelle\Model\Subscriber');
    }

    /**
     * Get all items.
     *
     * @return collect
     */
    public static function getAll()
    {
        return self::select('tracking_logs.*');
    }

    /**
     * Filter items.
     *
     * @return collect
     */
    public static function filter($request)
    {
        $user = $request->user();
        $customer = $user->customer;
        $query = self::select('tracking_logs.*');
        $query = $query->leftJoin('subscribers', 'subscribers.id', '=', 'tracking_logs.subscriber_id');
        $query = $query->leftJoin('campaigns', 'campaigns.id', '=', 'tracking_logs.campaign_id');
        $query = $query->leftJoin('sending_servers', 'sending_servers.id', '=', 'tracking_logs.sending_server_id');
        $query = $query->leftJoin('customers', 'customers.id', '=', 'tracking_logs.customer_id');

        // Keyword
        if (!empty(trim($request->keyword))) {
            foreach (explode(' ', trim($request->keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('campaigns.name', 'like', '%'.$keyword.'%')
                        ->orwhere('tracking_logs.status', 'like', '%'.$keyword.'%')
                        ->orwhere('sending_servers.name', 'like', '%'.$keyword.'%')
                        ->orwhere('subscribers.email', 'like', '%'.$keyword.'%');
                });
            }
        }

        // filters
        $filters = $request->all();
        if (!empty($filters)) {
            if (!empty($filters['campaign_uid'])) {
                $query = $query->where('campaigns.uid', '=', $filters['campaign_uid']);
            }
        }

        return $query;
    }

    public function scopeSent($query)
    {
        return $query->where('status', self::STATUS_SENT);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    /**
     * Search items.
     *
     * @return collect
     */
    public static function search($request, $campaign = null)
    {
        $query = self::filter($request);

        if (isset($campaign)) {
            $query = $query->where('tracking_logs.campaign_id', '=', $campaign->id);
        }

        $query = $query->orderBy($request->sort_order, $request->sort_direction);

        return $query;
    }

    /**
     * Items per page.
     *
     * @var array
     */
    public static $itemsPerPage = 25;
}
