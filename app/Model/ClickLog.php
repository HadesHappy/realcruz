<?php

/**
 * ClickLog class.
 *
 * Model class for click logs
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
use Illuminate\Support\Facades\Validator;

class ClickLog extends Model
{
    public static function createFromRequest($request)
    {
        $url = StringHelper::base64UrlDecode($request->url);

        try {
            self::validateUrl($url); // throw an exception if failed
        } catch (Exception $ex) {
            // just ignore and let users go
            // the 'url' validation of Laravel does not work with UTF8
            // For example: https://algeriestore.com/content/Algérie-Store-Catalogue-FR.pdf
            return [$url, null];
        }

        $messageId = StringHelper::base64UrlDecode($request->message_id);

        if (!TrackingLog::where('message_id', $messageId)->exists()) {
            return [$url, null];
        }

        $log = new self();
        $log->message_id = $messageId;
        $log->url = $url;
        $log->user_agent = array_key_exists('HTTP_USER_AGENT', $_SERVER) ? $_SERVER['HTTP_USER_AGENT'] : null;

        try {
            $location = IpLocation::add($request->ip());
            $log->ip_address = $location->ip_address;
        } catch (Exception $ex) {
            // Then no ip_address information
        }

        // Save anyway
        $log->save();

        // Just in case an open cannot be recorded (it is very likely the case, due to Gmail proxy/cache)
        // Make it make sense! there is at least one OPEN
        // @todo what about open callbacks?
        $openLog = $log->createRelatedOpenLogIfNotExist();

        // Do not trigger cache update if campaign is running
        if ($log->trackingLog && !is_null($log->trackingLog->campaign)) {
            if (!$log->trackingLog->campaign->isSending()) {
                event(new CampaignUpdated($log->trackingLog->campaign));
            }
        }

        return [$url, $log];
    }

    public function createRelatedOpenLogIfNotExist()
    {
        $openLog = OpenLog::firstOrNew(['message_id' => $this->message_id]);

        if (is_null($openLog->id)) {
            $openLog->save();
        }
    }

    private static function validateUrl($url)
    {
        $value = [ 'url' => $url ];
        $rules = [ 'url' => 'required|url' ];
        $validator = Validator::make($value, $rules);

        if ($validator->fails()) {
            throw new Exception('Invalid URL: '.$url);
        }
    }

    /**
     * Associations.
     *
     * @var object | collect
     */
    public function trackingLog()
    {
        return $this->belongsTo('Acelle\Model\TrackingLog', 'message_id', 'message_id');
    }

    /**
     * Get all items.
     *
     * @return collect
     */
    public static function getAll()
    {
        return self::select('click_logs.*');
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
        $query = self::select('click_logs.*');
        $query = $query->leftJoin('tracking_logs', 'click_logs.message_id', '=', 'tracking_logs.message_id');
        $query = $query->leftJoin('subscribers', 'subscribers.id', '=', 'tracking_logs.subscriber_id');
        $query = $query->leftJoin('campaigns', 'campaigns.id', '=', 'tracking_logs.campaign_id');
        $query = $query->leftJoin('sending_servers', 'sending_servers.id', '=', 'tracking_logs.sending_server_id');
        $query = $query->leftJoin('customers', 'customers.id', '=', 'tracking_logs.customer_id');

        // Keyword
        if (!empty(trim($request->keyword))) {
            foreach (explode(' ', trim($request->keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('campaigns.name', 'like', '%'.$keyword.'%')
                        ->orwhere('click_logs.ip_address', 'like', '%'.$keyword.'%')
                        ->orwhere('click_logs.url', 'like', '%'.$keyword.'%')
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

    /**
     * Items per page.
     *
     * @var array
     */
    public static $itemsPerPage = 25;
}
