<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Acelle\Model\Invoice;
use Acelle\Library\Traits\HasUid;
use Acelle\Library\Traits\HasQuota;
use Acelle\Library\Contracts\HasQuota as HasQuotaInterface;
use Acelle\Library\QuotaManager;
use Carbon\Carbon;

class Subscription extends Model implements HasQuotaInterface
{
    use HasUid;
    use HasQuota;

    public const STATUS_NEW = 'new';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_ENDED = 'ended';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'ends_at', 'current_period_ends_at',
        'created_at', 'updated_at', 'started_at', 'last_period_ends_at'
    ];

    /**
     * Indicates if the plan change should be prorated.
     *
     * @var bool
     */
    protected $prorate = true;

    /**
     * Associations.
     *
     * @var object | collect
     */
    public function plan()
    {
        // @todo dependency injection
        return $this->belongsTo('\Acelle\Model\Plan');
    }

    /**
     * Get the user that owns the subscription.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        // @todo dependency injection
        return $this->belongsTo('\Acelle\Model\Customer');
    }

    /**
     * Get related invoices.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoices()
    {
        $id = $this->id;
        $type = self::class;
        return Invoice::whereIn('id', function ($query) use ($id, $type) {
            $query->select('invoice_id')
            ->from(with(new InvoiceItem())->getTable())
            ->where('item_type', $type)
            ->where('item_id', $id);
        });
    }

    /**
     * Subscription only has one new invoice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getUnpaidInvoice()
    {
        return $this->invoices()
            ->unpaid()
            ->first();
    }

    public function scopeActive($query)
    {
        $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeEnded($query)
    {
        $query->where('status', self::STATUS_ENDED);
    }

    /**
     * Get last invoice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getItsOnlyUnpaidInitInvoice()
    {
        if (!$this->isNew()) {
            throw new \Exception('Method getItsOnlyUnpaidInitInvoice() only use for NEW subscription');
        }

        $query = $this->invoices()
            ->newSubscription()
            ->unpaid();

        // more than on invoice
        if ($query->count() != 1) {
            throw new \Exception('New Subscription must have only one unpaid TYPE_NEW_SUBSCRIPTION invoice!');
        }

        return $query->first();
    }

    /**
     * Get renew invoice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getItsOnlyUnpaidChangePlanInvoice()
    {
        return $this->invoices()
            ->changePlan()
            ->unpaid()
            ->first();
    }

    /**
     * Get change plan invoice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getItsOnlyUnpaidRenewInvoice()
    {
        return $this->invoices()
            ->renew()
            ->unpaid()
            ->first();
    }

    /**
     * Create init invoice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createInitInvoice()
    {
        //
        if ($this->getUnpaidInvoice()) {
            throw new \Exception(trans('messages.error.has_waiting_invoices'));
        }

        if ($this->plan->hasTrial()) {
            $endDate = $this->customer->formatDateTime($this->getTrialPeriodEndsAt(Carbon::now()), "date_full");
        } else {
            $endDate = $this->customer->formatDateTime($this->getPeriodEndsAt(Carbon::now()), "date_full");
        }

        // create init invoice
        return Invoice::createInvoice([
            'status' => Invoice::TYPE_NEW_SUBSCRIPTION,
            'title' => trans('messages.invoice.init_subscription'),
            'description' => trans('messages.invoice.init_subscription.desc', [
                'plan' => $this->plan->name,
                'date' => $endDate,
            ]),
            'customer_id' => $this->customer->id,
            'currency_id' => $this->plan->currency_id,
            'billingAddress' => $this->customer->getDefaultBillingAddress(),
            'metadata' => [
                'subscription_uid' => $this->uid,
            ],
            'items' => [
                [
                    'id' => $this->id,
                    'type' => get_class($this),
                    'amount' => $this->plan->hasTrial() ? 0 : $this->plan->price,
                    'title' => $this->plan->name,
                    'description' => view('plans._bill_desc', ['plan' => $this->plan]),
                ],
            ]
        ]);
    }

    /**
     * Create renew invoice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createRenewInvoice()
    {
        //
        if ($this->getUnpaidInvoice()) {
            throw new \Exception(trans('messages.error.has_waiting_invoices'));
        }

        // create invoice
        $invoice = new Invoice();
        $invoice->status = Invoice::STATUS_NEW;
        $invoice->type = Invoice::TYPE_RENEW_SUBSCRIPTION;
        $invoice->title = trans('messages.invoice.renew_subscription');
        $invoice->description = trans('messages.renew_subscription.desc', [
            'plan' => $this->plan->name,
            'date' => $this->customer->formatDateTime($this->nextPeriod(), 'date_full'),
        ]);
        $invoice->customer_id = $this->customer->id;
        $invoice->currency_id = $this->plan->currency_id;

        // default billing info
        $billingAddress = $this->customer->getDefaultBillingAddress();
        if ($billingAddress) {
            $invoice->billing_first_name = $billingAddress->first_name;
            $invoice->billing_last_name = $billingAddress->last_name;
            $invoice->billing_address = $billingAddress->address;
            $invoice->billing_email = $billingAddress->email;
            $invoice->billing_phone = $billingAddress->phone;
            $invoice->billing_country_id = $billingAddress->country_id;
        }

        // save
        $invoice->save();

        // add invoice number
        $invoice->createInvoiceNumber();

        // data
        $invoice->updateMetadata([
            'subscription_uid' => $this->uid,
        ]);

        // add item
        $invoiceItem = $invoice->invoiceItems()->create([
            'item_id' => $this->id,
            'item_type' => get_class($this),
            'amount' => $this->plan->price,
            'title' => $this->plan->name,
            'description' => view('plans._bill_desc', ['plan' => $this->plan]),
        ]);

        return $invoice;
    }

    /**
     * Create change plan invoice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createChangePlanInvoice($newPlan)
    {
        //
        if ($this->getUnpaidInvoice()) {
            $this->getUnpaidInvoice()->delete();
        }

        // calculate change plan amout ends at
        $metadata = $this->calcChangePlan($newPlan);

        // create invoice
        $invoice = new Invoice();
        $invoice->status = Invoice::STATUS_NEW;
        $invoice->type = Invoice::TYPE_CHANGE_PLAN;
        $invoice->title = trans('messages.invoice.change_plan');
        $invoice->description = trans('messages.change_plan.desc', [
            'plan' => $this->plan->name,
            'newPlan' => $newPlan->name,
            'date' => $this->customer->formatDateTime(Carbon::parse($metadata['endsAt']), 'date_full'),
        ]);
        $invoice->customer_id = $this->customer->id;
        $invoice->currency_id = $this->plan->currency_id;

        // default billing info
        $billingAddress = $this->customer->getDefaultBillingAddress();
        if ($billingAddress) {
            $invoice->billing_first_name = $billingAddress->first_name;
            $invoice->billing_last_name = $billingAddress->last_name;
            $invoice->billing_address = $billingAddress->address;
            $invoice->billing_email = $billingAddress->email;
            $invoice->billing_phone = $billingAddress->phone;
            $invoice->billing_country_id = $billingAddress->country_id;
        }

        // save
        $invoice->save();

        // add invoice number
        $invoice->createInvoiceNumber();

        // save data
        $invoice->updateMetadata([
            'subscription_uid' => $this->uid,
            'new_plan_uid' => $newPlan->uid,
        ]);

        // add item
        $invoiceItem = $invoice->invoiceItems()->create([
            'item_id' => $this->id,
            'item_type' => get_class($this),
            'amount' => $metadata['amount'],
            'title' => $newPlan->name,
            'description' => view('plans._bill_desc', ['plan' => $newPlan]),
        ]);

        return $invoice;
    }

    /**
     * Set subscription as ended.
     *
     * @return bool
     */
    public function setEnded()
    {
        // then set the sub end
        $this->status = self::STATUS_ENDED;
        $this->ends_at = Carbon::now();
        $this->save();
    }

    /**
     * Get lastest bill information
     *
     * @return void
     */
    public function getUpcomingBillingInfo()
    {
        if (!$this->getUnpaidInvoice()) {
            return null;
        }

        return $this->getUnpaidInvoice()->getBillingInfo();
    }

    /**
     * Get period by start date.
     *
     * @param  date  $date
     * @return date
     */
    public function getPeriodEndsAt($startDate)
    {
        return getPeriodEndsAt($startDate, $this->plan->frequency_amount, $this->plan->frequency_unit);
    }

    /**
     * Get trial period by start date.
     *
     * @param  date  $date
     * @return date
     */
    public function getTrialPeriodEndsAt($startDate)
    {
        return getPeriodEndsAt($startDate, $this->plan->trial_amount, $this->plan->trial_unit);
    }

    // 1 End luôn subscription nếu đã hết hạn
    // 2 Sinh ra RENEW invoice
    // 3 Xử lý thanh toán ==> #todo tach ra thành processInvoices()
    public function check()
    {
        switch ($this->status) {
            case self::STATUS_NEW:
                // nothing to check
                break;
            case self::STATUS_ACTIVE:
                // check expired
                if ($this->isExpired()) {
                    $this->cancelNow();
                    return;
                }

                // check expiring
                if ($this->isExpiring() && $this->canRenewPlan() && !$this->cancelled()) {
                    $pendingInvoice = $this->getUnpaidInvoice();

                    // create renew invoice if no pending invoice
                    if (!$pendingInvoice) {
                        $pendingInvoice = $this->createRenewInvoice();
                    }

                    // check if invoice is change plan -> do nothing
                    if ($pendingInvoice->isChangePlanInvoice()) {
                        return;
                    }
                }
                break;
            case self::STATUS_ENDED:
                // nothing to check
                break;
        }
    }

    public function processRenewInvoice()
    {
        if (!$this->isActive()) {
            return;
        }

        $invoice = $this->getItsOnlyUnpaidRenewInvoice();

        if (!$invoice) {
            return;
        }

        // not reach due date
        if (!$this->reachDueDate()) {
            return;
        }

        // check if customer can auto charge
        if (!$this->customer->preferredPaymentGatewayCanAutoCharge()) {
            return;
        }

        // auto charge
        $this->customer->getPreferredPaymentGateway()->autoCharge($invoice);
    }

    public function getDueDate()
    {
        return $this->current_period_ends_at->subDays(\Acelle\Model\Setting::get('recurring_charge_before_days'));
    }

    /**
     * reach due date.
     */
    public function reachDueDate()
    {
        return Carbon::now()->greaterThanOrEqualTo(
            $this->getDueDate()
        );
    }

    /**
     * Change plan.
     */
    public function changePlan($newPlan)
    {
        // calculate change plan amout ends at
        $metadata = $this->calcChangePlan($newPlan);

        // new plan
        $this->plan_id = $newPlan->id;

        // new end period
        $this->current_period_ends_at = $metadata['endsAt'];

        // update ends at if it exist
        if ($this->ends_at != null) {
            $this->ends_at = $this->current_period_ends_at;
        }

        $this->save();

        // logs
        $this->addLog(SubscriptionLog::TYPE_PLAN_CHANGED, [
            'old_plan' => $this->plan->name,
            'plan' => $newPlan->name,
        ]);
    }

    /**
     * Check subscription status.
     *
     * @param  Int  $subscriptionId
     * @return date
     */
    public static function checkAll()
    {
        $subscriptions = self::whereNull('ends_at')->orWhere('ends_at', '>=', Carbon::now())->get();
        foreach ($subscriptions as $subscription) {
            $subscription->check();
        }
    }

    /**
     * Associations.
     *
     * @var object | collect
     */
    public function subscriptionLogs()
    {
        // @todo dependency injection
        return $this->hasMany('\Acelle\Model\SubscriptionLog');
    }

    /**
     * Get all transactions from invoices.
     */
    public function transactions()
    {
        return \Acelle\Model\Transaction::whereIn('invoice_id', $this->invoices()->select('id'))
            ->orderBy('created_at', 'desc');
    }

    /**
     * Determine if the subscription is recurring and not on trial.
     *
     * @return bool
     */
    public function isRecurring()
    {
        return ! $this->cancelled();
    }

    /**
     * Determine if the subscription is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    /**
     * Determine if the subscription is active.
     *
     * @return bool
     */
    public function isNew()
    {
        return $this->status == self::STATUS_NEW;
    }

    /**
     * Determine if the subscription is no longer active.
     *
     * @return bool
     */
    public function cancelled()
    {
        return ! is_null($this->ends_at);
    }

    /**
     * Determine if the subscription is ended.
     *
     * @return bool
     */
    public function isEnded()
    {
        return $this->status == self::STATUS_ENDED;
    }

    /**
     * Determine if the subscription is pending.
     *
     * @return bool
     */
    public function activate()
    {
        if (!$this->isNew()) {
            throw new \Exception("Only new subscription can be activated, double check your code to make sure you call activate() on a new subscription");
        }

        if ($this->plan->hasTrial()) {
            $this->current_period_ends_at = $this->getTrialPeriodEndsAt(Carbon::now());
        } else {
            $this->current_period_ends_at = $this->getPeriodEndsAt(Carbon::now());
        }
        $this->ends_at = null;
        $this->status = self::STATUS_ACTIVE;
        $this->started_at = Carbon::now();
        $this->save();

        // add log
        $this->addLog(SubscriptionLog::TYPE_SUBSCRIBED, [
            'plan' => $this->plan->name,
            'price' => $this->plan->getFormattedPrice(),
        ]);
    }

    /**
     * Next one period to subscription.
     *
     * @param  Gateway    $gateway
     * @return Boolean
     */
    public function nextPeriod()
    {
        return $this->getPeriodEndsAt($this->current_period_ends_at);
    }

    /**
     * Next one period to subscription.
     *
     * @param  Gateway    $gateway
     * @return Boolean
     */
    public function periodStartAt()
    {
        $startAt = $this->current_period_ends_at;
        $interval = $this->plan->frequency_unit;
        $intervalCount = $this->plan->frequency_amount;

        switch ($interval) {
            case 'month':
                $startAt = $startAt->subMonthsNoOverflow($intervalCount);
                break;
            case 'day':
                $startAt = $startAt->subDay($intervalCount);
                // no break
            case 'week':
                $startAt = $startAt->subWeek($intervalCount);
                break;
            case 'year':
                $startAt = $startAt->subYearsNoOverflow($intervalCount);
                break;
            default:
                $startAt = null;
        }

        return $startAt;
    }

    /**
     * Check if subscription is expired.
     *
     * @param  Int  $subscriptionId
     * @return date
     */
    public function isExpired()
    {
        return isset($this->ends_at) && Carbon::now()->endOfDay() > $this->ends_at;
    }

    /**
     * Subscription transactions.
     *
     * @return array
     */
    public function getLogs()
    {
        return $this->subscriptionLogs()->orderBy('created_at', 'desc')->get();
    }

    /**
     * Subscription transactions.
     *
     * @return array
     */
    public function addLog($type, $data, $transaction_id=null)
    {
        $log = new SubscriptionLog();
        $log->subscription_id = $this->id;
        $log->type = $type;
        $log->transaction_id = $transaction_id;
        $log->save();

        if (isset($data)) {
            $log->updateData($data);
        }

        return $log;
    }

    /**
     * Cancel subscription. Set ends at to the end of period.
     *
     * @return void
     */
    public function cancel()
    {
        $this->ends_at = $this->current_period_ends_at;
        $this->save();

        // delete pending invoice
        if ($this->getUnpaidInvoice()) {
            $this->getUnpaidInvoice()->delete();
        }
    }

    /**
     * Cancel subscription. Set ends at to the end of period.
     *
     * @return void
     */
    public function resume()
    {
        if ($this->isEnded()) {
            throw new Exception('Subscription is ended. Can not change ended subscription state!');
        }

        if (!$this->cancelled()) {
            throw new Exception('Subscription is not cancelled. No need to resume again.');
        }

        $this->ends_at = null;
        $this->save();
    }

    /**
     * Cancel subscription. Set ends at to the end of period.
     *
     * @return void
     */
    public function cancelNow()
    {
        if ($this->isEnded()) {
            throw new Exception('Subscription is already ended');
        }

        // set status = ended
        $this->setEnded();

        // cancel all pending invoices (new)
        $this->invoices()->new()->delete();

        // add log
        $this->addLog(SubscriptionLog::TYPE_CANCELLED_NOW, [
            'plan' => $this->plan->name,
            'price' => $this->plan->getFormattedPrice(),
        ]);
    }

    /**
     * Renew subscription
     *
     * @return void
     */
    public function renew()
    {
        // set new current period
        $this->current_period_ends_at = $this->getPeriodEndsAt($this->current_period_ends_at);

        // ends at
        if ($this->ends_at != null) {
            $this->ends_at = $this->current_period_ends_at;
        }

        $this->save();

        // logs
        $this->addLog(SubscriptionLog::TYPE_RENEWED, [
            'plan' => $this->plan->name,
            'price' => $this->plan->getFormattedPrice(),
        ]);
    }

    public function isExpiring()
    {
        if (!$this->current_period_ends_at) {
            return false;
        }

        // check if recurring accur
        if (Carbon::now()->greaterThanOrEqualTo($this->current_period_ends_at->subDays(Setting::get('end_period_last_days')))) {
            return true;
        }

        return false;
    }

    /**
     * Check if can renew free plan. amount > 0 or == 0 && renew_free_plan setting = true
     *
     * @return void
     */
    public function canRenewPlan()
    {
        return ($this->plan->price > 0 ||
            (Setting::isYes('renew_free_plan') && $this->plan->price == 0)
        );
    }

    /**
     * user want to change plan.
     *
     * @return bollean
     */
    public function calcChangePlan($plan)
    {
        if (($this->plan->frequency_unit != $plan->frequency_unit) ||
            ($this->plan->frequency_amount != $plan->frequency_amount) ||
            ($this->plan->currency->code != $plan->currency->code)
        ) {
            throw new \Exception(trans('messages.can_not_change_to_diff_currency_period_plan'));
        }

        // new ends at
        $newEndsAt = $this->current_period_ends_at;

        // amout per day of current plan
        $currentAmount = $this->plan->price;
        $periodDays = $this->current_period_ends_at->diffInDays($this->periodStartAt()->startOfDay());
        $remainDays = $this->current_period_ends_at->diffInDays(Carbon::now()->startOfDay());
        $currentPerDayAmount = ($currentAmount/$periodDays);
        $newAmount = ($plan->price/$periodDays)*$remainDays;
        $remainAmount = $currentPerDayAmount*$remainDays;

        $amount = $newAmount - $remainAmount;

        // if amount < 0
        if ($amount < 0) {
            $days = (int) ceil(-($amount/$currentPerDayAmount));
            $amount = 0;
            $newEndsAt->addDays($days);

            // if free plan
            if ($plan->price == 0) {
                $newEndsAt = $this->current_period_ends_at;
            }
        }

        return [
            'amount' => round($amount, 2),
            'endsAt' => $newEndsAt,
        ];
    }

    public function abortNew()
    {
        if (!$this->isNew()) {
            throw new \Exception('This subscription is not NEW. Can not abortNew!');
        }

        $this->getItsOnlyUnpaidInitInvoice()->delete();

        // if subscription is new -> cancel now subscription.
        // Make sure a new subscription must have a pending invoice
        $this->cancelNow();
    }

    public function getCreditsUsedDuringPlanCycle($name)
    {
        $interval = "{$this->plan->frequency_amount} {$this->plan->frequency_unit}"; // '1 month' or '1 year' for example
        $from = Carbon::now()->subtract($interval);
        return $this->getCreditsUsed($name, $from);
    }

    public function getCreditsLimit($name)
    {
        if ($name != 'send') {
            throw new Exception("`{$name}` credis limit is not available");
        }

        return $this->plan->getOption('email_max');
    }

    public function getCreditsUsedPercentageDuringPlanCycle($name)
    {
        $creditsUsed = $this->getCreditsUsedDuringPlanCycle($name);
        $creditsLimit = $this->plan->getOption('email_max');

        // if = -1 (default:unlimited) return 0
        // if = 0 return 0
        if (!$creditsLimit || $creditsLimit == -1) {
            return 0;
        }

        $percentage = ($creditsLimit == QuotaManager::QUOTA_UNLIMITED) ? 0 : $creditsUsed / $creditsLimit;

        return $percentage;
    }

    /***   IMPLEMENTATION OF HasQuotaInterface ***/
    public function getQuotaSettings($name): ?array
    {
        return $this->plan->getQuotaSettings($name);
    }
}
