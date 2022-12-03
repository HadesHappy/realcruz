<?php

/**
 * OpenLog class.
 *
 * Model class for open logging
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
use Acelle\Events\CampaignUpdated;
use Acelle\Library\StringHelper;
use Exception;

class OpenLog extends Model
{
    protected $fillable = ['message_id'];

    /**
     * Associations.
     *
     * @var object | collect
     */
    public function trackingLog()
    {
        return $this->belongsTo('Acelle\Model\TrackingLog', 'message_id', 'message_id');
    }
    public function ipLocation()
    {
        return $this->belongsTo('Acelle\Model\IpLocation', 'ip_address', 'ip_address');
    }

    /**
     * Get all items.
     *
     * @return collect
     */
    public static function getAll()
    {
        return self::select('open_logs.*');
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
        $query = self::select('open_logs.*');
        $query = $query->leftJoin('tracking_logs', 'open_logs.message_id', '=', 'tracking_logs.message_id');
        $query = $query->leftJoin('subscribers', 'subscribers.id', '=', 'tracking_logs.subscriber_id');
        $query = $query->leftJoin('campaigns', 'campaigns.id', '=', 'tracking_logs.campaign_id');
        $query = $query->leftJoin('sending_servers', 'sending_servers.id', '=', 'tracking_logs.sending_server_id');
        $query = $query->leftJoin('customers', 'customers.id', '=', 'tracking_logs.customer_id');

        // Keyword
        if (!empty(trim($request->keyword))) {
            foreach (explode(' ', trim($request->keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('campaigns.name', 'like', '%'.$keyword.'%')
                        ->orwhere('open_logs.ip_address', 'like', '%'.$keyword.'%')
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

    public static function createFromRequest($request)
    {
        $ipAddress = $request->ip();

        /****
         * Also record Google proxy opens
         *
         *
        if (!StringHelper::fromHumanIpAddress($ipAddress)) {
            return;
        }
         *
         */

        $messageId = StringHelper::base64UrlDecode($request->message_id);

        if (!TrackingLog::where('message_id', $messageId)->exists()) {
            throw new Exception(sprintf('Message ID %s not found', $messageId));
        }

        $log = new self();
        $log->message_id = $messageId;
        $log->user_agent = array_key_exists('HTTP_USER_AGENT', $_SERVER) ? $_SERVER['HTTP_USER_AGENT'] : null;

        try {
            $location = IpLocation::add($ipAddress);
            $log->ip_address = $location->ip_address;
        } catch (Exception $ex) {
            // Then no GeoIP information
            // open.ip_address is NULL
        }

        $log->save();

        // Do not trigger cache update if campaign is running
        if ($log->trackingLog && !is_null($log->trackingLog->campaign)) {
            if (!$log->trackingLog->campaign->isSending()) {
                event(new CampaignUpdated($log->trackingLog->campaign));
            }
        }

        return $log;
    }

    /**
     * Items per page.
     *
     * @var array
     */
    public static $itemsPerPage = 25;
}
