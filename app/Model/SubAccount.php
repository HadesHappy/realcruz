<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Acelle\Library\Traits\HasUid;

class SubAccount extends Model
{
    use HasUid;

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
    public function sendingServer()
    {
        return $this->belongsTo('Acelle\Model\SendingServer');
    }

    public function customer()
    {
        return $this->belongsTo('Acelle\Model\Customer');
    }

    public function subscriptions()
    {
        return $this->hasMany('Acelle\Model\Subscription', 'sub_account_id', 'id');
    }

    /**
     * Filter items.
     *
     * @return collect
     */
    public static function filter($request)
    {
        $user = $request->user();
        $query = self::select('sub_accounts.*');
        $query = $query->leftJoin('sending_servers', 'sending_servers.id', '=', 'sub_accounts.sending_server_id');
        $query = $query->leftJoin('customers', 'customers.id', '=', 'sub_accounts.customer_id');

        // Keyword
        if (!empty(trim($request->keyword))) {
            foreach (explode(' ', trim($request->keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('sub_accounts.username', 'like', '%'.$keyword.'%');
                });
            }
        }

        if (!empty($request->admin_id)) {
            $query = $query->where('sending_servers.admin_id', '=', $request->admin_id);
        }

        // filters
        $filters = $request->all();
        if (!empty($filters)) {
            if (!empty($filters['type'])) {
                $query = $query->where('sending_servers.type', '=', $filters['type']);
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

        if (!empty($request->sort_order)) {
            $query = $query->orderBy($request->sort_order, $request->sort_direction);
        }

        return $query;
    }

    /**
     * Get secured api key.
     *
     * @return collect
     */
    public function getSecuredApiKey()
    {
        return substr($this->api_key, 0, 4).'...'.substr($this->api_key, -4);
    }
}
