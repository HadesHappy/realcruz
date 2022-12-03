<?php

/**
 * CustomerGroup class.
 *
 * Model class for customer group
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

class CustomerGroup extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'options',
    ];

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
     * Items per page.
     *
     * @var array
     */
    public static $itemsPerPage = 25;

    /**
     * The rules for validation.
     *
     * @var array
     */
    public static $rules = array(
        'name' => 'required',
    );

    /**
     * Rules.
     *
     * @return array
     */
    public static function rules()
    {
        $rules = [
            'name' => 'required',
        ];

        $options = self::defaultOptions();
        foreach ($options as $type => $option) {
            $rules['options.'.$type] = 'required';
        }

        return $rules;
    }

    /**
     * Associations.
     *
     * @var object | collect
     */
    public function customers()
    {
        return $this->hasMany('Acelle\Model\Customer');
    }

    public function customer_group_sending_servers()
    {
        return $this->hasMany('Acelle\Model\CustomerGroupSendingServer');
    }

    public function sending_servers()
    {
        return $this->belongsToMany('Acelle\Model\SendingServer', 'customer_group_sending_servers');
    }

    /**
     * Filter items.
     *
     * @return collect
     */
    public static function filter($request)
    {
        $user = $request->user();
        $query = self::select('customer_groups.*');

        if (!$user->can('read_all', new self())) {
            $query = $query->where('customer_groups.admin_id', '=', $user->admin->id);
        }

        // Keyword
        if (!empty(trim($request->keyword))) {
            $query = $query->where('name', 'like', '%'.$request->keyword.'%');
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

    /**
     * Get select options.
     *
     * @return array
     */
    public static function getSelectOptions()
    {
        $options = self::getAll()->get()->map(function ($item) {
            return ['value' => $item->id, 'text' => $item->name];
        });

        return $options;
    }

    /**
     * Default options for new groups.
     *
     * @return array
     */
    public static function defaultOptions()
    {
        return [
            'list_max' => '-1',
            'subscriber_max' => '-1',
            'subscriber_per_list_max' => '-1',
            'segment_per_list_max' => '3',
            'campaign_max' => '-1',
            'sending_quota' => '-1',
            'sending_quota_time' => '-1',
            'sending_quota_time_unit' => 'month',
            'max_process' => '1',
            'all_sending_servers' => 'yes',
            'max_size_upload_total' => '500',
            'max_file_size_upload' => '5',
            'unsubscribe_url_required' => 'yes',
            'access_when_offline' => 'no',
        ];
    }

    /**
     * Get options.
     *
     * @return array
     */
    public function getOptions()
    {
        if (empty($this->options)) {
            return self::defaultOptions();
        } else {
            $defaul_options = self::defaultOptions();
            $saved_options = json_decode($this->options, true);
            foreach ($defaul_options as $x => $group) {
                if (isset($saved_options[$x])) {
                    $defaul_options[$x] = $saved_options[$x];
                }
            }

            return $defaul_options;
        }
    }

    /**
     * Get option.
     *
     * @return string
     */
    public function getOption($name)
    {
        return $this->getOptions()[$name];
    }

    /**
     * Save options.
     *
     * @return array
     */
    public function saveOptions($options)
    {
        return true;
    }

    /**
     * Quota time unit options.
     *
     * @return array
     */
    public static function timeUnitOptions()
    {
        return [
            ['value' => 'minute', 'text' => trans('messages.minute')],
            ['value' => 'hour', 'text' => trans('messages.hour')],
            ['value' => 'day', 'text' => trans('messages.day')],
            ['value' => 'week', 'text' => trans('messages.week')],
            ['value' => 'month', 'text' => trans('messages.month')],
            ['value' => 'year', 'text' => trans('messages.year')],
        ];
    }

    /**
     * Get sending servers ids.
     *
     * @return array
     */
    public function getSendingServerIds()
    {
        $arr = [];
        foreach ($this->sending_servers as $server) {
            $arr[] = $server->uid;
        }

        return $arr;
    }

    /**
     * Update sending servers.
     *
     * @return array
     */
    public function updateSendingServers($servers)
    {
        $this->customer_group_sending_servers()->delete();
        foreach ($servers as $key => $param) {
            if ($param['check']) {
                $server = SendingServer::findByUid($key);
                $row = new CustomerGroupSendingServer();
                $row->customer_group_id = $this->id;
                $row->sending_server_id = $server->id;
                $row->fitness = $param['fitness'];
                $row->save();
            }
        }
    }

    /**
     * Multi process select options.
     *
     * @return array
     */
    public static function multiProcessSelectOptions()
    {
        $options = [['value' => 1, 'text' => trans('messages.one_single_process')]];
        for ($i = 2; $i < 101; ++$i) {
            $options[] = ['value' => $i, 'text' => $i];
        }

        return $options;
    }

    /**
     * Display group quota.
     *
     * @return array
     */
    public function displayQuota()
    {
        if ($this->getOption('sending_quota') == -1) {
            return trans('messages.unlimited');
        } elseif ($this->getOption('sending_quota_time') == -1) {
            return $this->getOption('sending_quota');
        } else {
            return $this->getOption('sending_quota').' '.trans('messages.'.\Acelle\Library\Tool::getPluralPrase('email', $this->getOption('sending_quota'))).' / '.$this->getOption('sending_quota_time').' '.trans('messages.'.\Acelle\Library\Tool::getPluralPrase($this->getOption('sending_quota_time_unit'), $this->getOption('sending_quota')));
        }
    }

    /**
     * Display max lists.
     *
     * @return array
     */
    public function displayMaxList()
    {
        if ($this->getOption('list_max') == -1) {
            return trans('messages.unlimited');
        } else {
            return $this->getOption('list_max');
        }
    }

    /**
     * Display max subscribers.
     *
     * @return array
     */
    public function displayMaxSubscriber()
    {
        if ($this->getOption('subscriber_max') == -1) {
            return trans('messages.unlimited');
        } else {
            return $this->getOption('subscriber_max');
        }
    }

    /**
     * Display max campaign.
     *
     * @return array
     */
    public function displayMaxCampaign()
    {
        if ($this->getOption('campaign_max') == -1) {
            return trans('messages.unlimited');
        } else {
            return $this->getOption('campaign_max');
        }
    }

    /**
     * Display max campaign.
     *
     * @return array
     */
    public function displayMaxSizeUploadTotal()
    {
        if ($this->getOption('max_size_upload_total') == -1) {
            return trans('messages.unlimited');
        } else {
            return $this->getOption('max_size_upload_total');
        }
    }

    /**
     * Display max campaign.
     *
     * @return array
     */
    public function displayFileSizeUpload()
    {
        if ($this->getOption('max_file_size_upload') == -1) {
            return trans('messages.unlimited');
        } else {
            return $this->getOption('max_file_size_upload');
        }
    }

    /**
     * Display sending servers count.
     *
     * @return array
     */
    public function displaySendingServersCount()
    {
        if ($this->getOption('all_sending_servers') == 'yes') {
            return trans('messages.all');
        } else {
            return $this->user_group_sending_servers()->count();
        }
    }

    /**
     * Api rules.
     *
     * @return array
     */
    public static function apiRules()
    {
        $rules = [
            'name' => 'required',
        ];

        return $rules;
    }

    /**
     * Update options from request.
     */
    public function updateOptions($options)
    {
        if (is_array($options)) {
            $defaul_options = self::defaultOptions();
            $saved_options = $options;
            foreach ($defaul_options as $x => $group) {
                foreach ($group as $y => $option) {
                    if (isset($saved_options[$x]) && isset($saved_options[$x][$y])) {
                        $defaul_options[$x][$y] = $saved_options[$x][$y];
                    }
                }
            }
            $this->options = json_encode($defaul_options, true);
        }
    }
}
