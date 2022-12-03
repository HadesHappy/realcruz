<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Acelle\Library\Traits\HasUid;

class SubscriptionLog extends Model
{
    use HasUid;

    public const TYPE_SUBSCRIBE = 'subscribe';
    public const TYPE_SUBSCRIBED = 'subscribed';
    public const TYPE_PAID = 'paid';
    public const TYPE_CLAIMED = 'claimed';
    public const TYPE_UNCLAIMED = 'unclaimed';
    public const TYPE_STARTED = 'started';
    public const TYPE_EXPIRED = 'expired';
    public const TYPE_RENEWED = 'renewed';
    public const TYPE_RENEW = 'renew';
    public const TYPE_RENEW_FAILED = 'renew_failed';
    public const TYPE_PLAN_CHANGE = 'plan_change';
    public const TYPE_PLAN_CHANGE_CANCELED = 'plan_change_canceled';
    public const TYPE_PLAN_CHANGED = 'plan_changed';
    public const TYPE_PLAN_CHANGE_FAILED = 'plan_change_failed';
    public const TYPE_CANCELLED = 'cancelled';
    public const TYPE_CANCELLED_NOW = 'cancelled_now';
    public const TYPE_ADMIN_APPROVED = 'admin_approved';
    public const TYPE_ADMIN_REJECTED = 'admin_rejected';
    public const TYPE_ADMIN_RENEW_APPROVED = 'admin_renew_approved';
    public const TYPE_ADMIN_PLAN_CHANGE_APPROVED = 'admin_plan_change_approved';
    public const TYPE_ADMIN_RENEW_REJECTED = 'admin_renew_rejected';
    public const TYPE_ADMIN_PLAN_CHANGE_REJECTED = 'admin_plan_change_rejected';
    public const TYPE_ADMIN_CANCELLED = 'admin_cancelled';
    public const TYPE_ADMIN_CANCELLED_NOW = 'admin_cancelled_now';
    public const TYPE_ADMIN_RESUMED = 'admin_resumed';
    public const TYPE_ADMIN_PLAN_ASSIGNED = 'admin_plan_assigned';
    public const TYPE_RESUMED = 'resumed';
    public const TYPE_ERROR = 'error';


    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type'
    ];

    /**
     * Associations.
     *
     * @var object | collect
     */
    public function subscription()
    {
        // @todo dependency injection
        return $this->belongsTo('\Acelle\Model\Subscription');
    }

    /**
     * Get metadata.
     *
     * @var object | collect
     */
    public function getData()
    {
        if (!$this->data) {
            return json_decode('{}', true);
        }

        return json_decode($this->data, true);
    }

    /**
     * Get metadata.
     *
     * @var object | collect
     */
    public function updateData($data)
    {
        $data = (object) array_merge((array) $this->getData(), $data);
        $this->data = json_encode($data);

        $this->save();
    }
}
