<?php

/**
 * Log class.
 *
 * Model class for log engine
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

class Log extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id', 'type', 'name', 'data',
    ];

    /**
     * Items per page.
     *
     * @var array
     */
    public static $itemsPerPage = 25;

    /**
     * Associations.
     *
     * @var object | collect
     */
    public function customer()
    {
        return $this->belongsTo('Acelle\Model\Customer');
    }

    /**
     * Display log content.
     *
     * @var string
     */
    public function message()
    {
        $data = (array) json_decode($this->data);
        if (isset($data['name'])) {
            $data['name'] = strip_tags($data['name']);
        }

        // Campaign
        if ($this->type == 'campaign') {
            if (isset($data['list_id'])) {
                $list = MailList::find($data['list_id']);
                $list_link = is_object($list) ? action('MailListController@show', $list->uid) : '#deleted';
                $data['list_link'] = $list_link;
            }

            $item = Campaign::find($data['id']);
            $link = is_object($item) ? action('CampaignController@overview', $item->uid) : '#deleted';

            $data['link'] = $link;
        }
        // Subscriber
        elseif ($this->type == 'subscriber') {
            $list = MailList::find($data['list_id']);
            $list_link = is_object($list) ? action('MailListController@overview', $list->uid) : '#deleted';
            $data['list_link'] = $list_link;

            $item = Subscriber::find($data['id']);
            $link = is_object($item) && is_object($list) ? action('SubscriberController@edit', ['uid' => $item->uid, 'list_uid' => $list->uid]) : '#deleted';
            $data['link'] = $link;
        }
        // Segment
        elseif ($this->type == 'segment') {
            $list = MailList::find($data['list_id']);
            $list_link = is_object($list) ? action('MailListController@overview', $list->uid) : '#deleted';
            $data['list_link'] = $list_link;

            $item = Segment::find($data['id']);
            $link = is_object($item) && is_object($list) ? action('SegmentController@edit', ['uid' => $item->uid, 'list_uid' => $list->uid]) : '#deleted';
            $data['link'] = $link;
        }
        // Page
        elseif ($this->type == 'page') {
            $list = MailList::find($data['list_id']);
            $list_link = is_object($list) ? action('MailListController@overview', $list->uid) : '#deleted';
            $data['list_link'] = $list_link;

            $item = Page::find($data['id']);
            $link = is_object($item) && is_object($list) ? action('PageController@update', ['list_uid' => $list->uid, 'alias' => $data['alias']]) : '#deleted';
            $data['link'] = $link;
            $data['name'] = trans('messages.'.$data['alias']);
        }
        // List
        elseif ($this->type == 'list') {
            $item = MailList::find($data['id']);
            $link = is_object($item) ? action('MailListController@overview', $item->uid) : '#deleted';
            $data['link'] = $link;

            if (isset($data['from_uid'])) {
                $from = MailList::findByUid($data['from_uid']);
                $from_link = is_object($from) ? action('MailListController@overview', $data['from_uid']) : '#deleted';
                $data['from_link'] = $from_link;
            }
            if (isset($data['to_uid'])) {
                $to = MailList::findByUid($data['to_uid']);
                $to_link = is_object($to) ? action('MailListController@overview', $data['to_uid']) : '#deleted';
                $data['to_link'] = $to_link;
            }
        }
        // Campaign
        elseif ($this->type == 'automation') {
            if (isset($data['list_id'])) {
                $list = MailList::find($data['list_id']);
                $list_link = is_object($list) ? action('MailListController@show', $list->uid) : '#deleted';
                $data['list_link'] = $list_link;
            }

            $item = Automation2::find($data['id']);
            $link = is_object($item) ? action('Automation2Controller@edit', $item->uid) : '#deleted';

            $data['link'] = $link;
        }
        // Sending server
        elseif ($this->type == 'sending_server') {
            $item = SendingServer::find($data['id']);
            $link = is_object($item) ? action('SendingServerController@edit', ['id' => $item->uid, 'type' => $item->type]) : '#deleted';
            $data['link'] = $link;
        }
        // Sending server
        elseif ($this->type == 'sending_domain') {
            $item = SendingDomain::find($data['id']);
            $link = is_object($item) ? action('SendingDomainController@edit', $item->uid) : '#deleted';
            $data['link'] = $link;
        }
        // Email verification server
        elseif ($this->type == 'email_verification_server') {
            $item = EmailVerificationServer::find($data['id']);
            $link = is_object($item) ? action('EmailVerificationServerController@edit', $item->uid) : '#deleted';
            $data['link'] = $link;
        }

        $message = trans('messages.log.'.$this->type.'.'.$this->name, $data);

        return $message;
    }

    /**
     * Filter items.
     *
     * @return collect
     */
    public static function filter($request)
    {
        $customer = $request->user()->customer;
        $query = self::where('customer_id', '=', $customer->id);

        // Keyword
        if (!empty(trim($request->keyword))) {
            $query = $query->where('name', 'like', '%'.$request->keyword.'%');
        }

        // filters
        $filters = $request->all();
        if (!empty($filters)) {
            if (!empty($filters['type'])) {
                $query = $query->where('logs.type', '=', $filters['type']);
            }
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

        $query = $query->orderBy($request->sort_order, $request->sort_direction);

        return $query;
    }
}
