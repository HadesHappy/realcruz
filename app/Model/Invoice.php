<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;

use Acelle\Model\Subscription;
use Acelle\Model\Transaction;
use Acelle\Library\Traits\HasUid;
use Dompdf\Dompdf;
use Acelle\Library\StringHelper;
use function Acelle\Helpers\getAppHost;

class Invoice extends Model
{
    use HasUid;

    // statuses
    public const STATUS_NEW = 'new';               // unpaid
    public const STATUS_PAID = 'paid';

    // type
    public const TYPE_RENEW_SUBSCRIPTION = 'renew_subscription';
    public const TYPE_NEW_SUBSCRIPTION = 'new_subscription';
    public const TYPE_CHANGE_PLAN = 'change_plan';

    protected $fillable = [
        'billing_first_name',
        'billing_last_name',
        'billing_address',
        'billing_email',
        'billing_phone',
        'billing_country_id',
    ];

    public function scopeNew($query)
    {
        $query->whereIn('status', [
            self::STATUS_NEW,
        ]);
    }

    public function scopeUnpaid($query)
    {
        $query->whereIn('status', [
            self::STATUS_NEW,
        ]);
    }

    public function scopeChangePlan($query)
    {
        $query->where('type', self::TYPE_CHANGE_PLAN);
    }

    public function scopeRenew($query)
    {
        $query->where('type', self::TYPE_RENEW_SUBSCRIPTION);
    }

    public function scopeNewSubscription($query)
    {
        $query->whereIn('type', [
            self::TYPE_NEW_SUBSCRIPTION,
        ]);
    }

    /**
     * Invoice currency.
     */
    public function currency()
    {
        return $this->belongsTo('Acelle\Model\Currency');
    }

    /**
     * Invoice customer.
     */
    public function customer()
    {
        return $this->belongsTo('Acelle\Model\Customer');
    }

    /**
     * Invoice items.
     */
    public function invoiceItems()
    {
        return $this->hasMany('Acelle\Model\InvoiceItem');
    }

    /**
     * Transactions.
     */
    public function transactions()
    {
        return $this->hasMany('Acelle\Model\Transaction');
    }

    public function billingCountry()
    {
        return $this->belongsTo('Acelle\Model\Country', 'billing_country_id');
    }

    /**
     * Get pending transaction.
     */
    public function getPendingTransaction()
    {
        return $this->transactions()
            ->where('status', \Acelle\Model\Transaction::STATUS_PENDING)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Last transaction.
     */
    public function lastTransaction()
    {
        return $this->transactions()
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Last transaction is failed.
     */
    public function lastTransactionIsFailed()
    {
        if ($this->lastTransaction()) {
            return $this->lastTransaction()->isFailed();
        } else {
            return false;
        }
    }

    /**
     * Set as pending.
     *
     * @return void
     */
    public function setPending()
    {
        $this->status = self::STATUS_PENDING;
        $this->save();
    }

    /**
     * Set as paid.
     *
     * @return void
     */
    public function setPaid()
    {
        $this->status = self::STATUS_PAID;
        $this->save();
    }

    public function getTax()
    {
        $total = 0;

        foreach ($this->invoiceItems as $item) {
            $total += $item->getTax();
        }

        return $total;
    }

    public function subTotal()
    {
        $total = 0;

        foreach ($this->invoiceItems as $item) {
            $total += $item->subTotal();
        }

        return $total;
    }

    public function total()
    {
        $total = 0;

        foreach ($this->invoiceItems as $item) {
            $total += $item->total();
        }

        return $total + $this->fee;
    }

    /**
     * formatted Total.
     *
     * @return void
     */
    public function formattedTotal()
    {
        return format_price($this->total(), $this->currency->format);
    }

    /**
     * Get metadata.
     *
     * @var object | collect
     */
    public function getMetadata($name=null)
    {
        if (!$this['metadata']) {
            return json_decode('{}', true);
        }

        $data = json_decode($this['metadata'], true);

        if ($name != null) {
            if (isset($data[$name])) {
                return $data[$name];
            } else {
                return null;
            }
        } else {
            return $data;
        }
    }

    /**
     * Get metadata.
     *
     * @var object | collect
     */
    public function updateMetadata($data)
    {
        $metadata = (object) array_merge((array) $this->getMetadata(), $data);
        $this['metadata'] = json_encode($metadata);

        $this->save();
    }

    // /**
    //  * Get type.
    //  *
    //  * @return void
    //  */
    // public function getType()
    // {
    //     return $this->invoiceItems()->first()->item_type;
    // }

    /**
     * Check new.
     *
     * @return void
     */
    public function isNew()
    {
        return $this->status == self::STATUS_NEW;
    }

    /**
     * set status as new.
     *
     * @return void
     */
    public function setNew()
    {
        $this->status = self::STATUS_NEW;
        $this->save();
    }

    /**
     * Approve invoice.
     *
     * @return void
     */
    public function approve()
    {
        // for only new invoice
        if (!$this->isNew() || !$this->getPendingTransaction()) {
            throw new \Exception("Trying to approve an invoice that is not NEW or does not have a pending transaction (Invoice ID: {$this->id}, status: {$this->status}");
        }

        // fulfill invoice
        $this->fulfill();
    }

    /**
     * Reject invoice.
     *
     * @return void
     */
    public function reject($error)
    {
        // for only new invoice
        if (!$this->isNew() || !$this->getPendingTransaction()) {
            throw new \Exception("Trying to approve an invoice that is not NEW or does not have a pending transaction (Invoice ID: {$this->id}, status: {$this->status}");
        }

        // fulfill invoice
        $this->payFailed($error);
    }

    /**
     * Pay invoice.
     *
     * @return void
     */
    public function fulfill()
    {
        // set status as paid
        $this->setPaid();

        // set transaction as success
        // Important: according to current design, the rule is: one invoice only has one pending transaction
        if ($this->getPendingTransaction()) {
            $this->getPendingTransaction()->setSuccess();
        }

        // invoice after pay actions
        $this->process();
    }

    /**
     * Pay invoice failed.
     *
     * @return void
     */
    public function payFailed($error)
    {
        $this->getPendingTransaction()->setFailed(trans('messages.payment.cannot_charge', [
            'id' => $this->uid,
            'error' => $error,
            'service' => $this->getPendingTransaction()->method,
        ]));
    }

    /**
     * Process invoice.
     *
     * @return void
     */
    public function process()
    {
        $data = $this->getMetadata();
        $subscription = Subscription::findByUid($data['subscription_uid']);

        switch ($this->type) {
            case self::TYPE_NEW_SUBSCRIPTION:
                $subscription->activate();
                break;
            case self::TYPE_RENEW_SUBSCRIPTION:
                $subscription->renew();
                break;
            case self::TYPE_CHANGE_PLAN:
                $newPlan = \Acelle\Model\Plan::findByUid($data['new_plan_uid']);
                $subscription->changePlan($newPlan);
                break;
            default:
                throw new \Exception('Invoice type is not valid: ' . $this->type);
        }
    }

    /**
     * Check paid.
     *
     * @return void
     */
    public function isPaid()
    {
        return $this->status == self::STATUS_PAID;
    }

    /**
     * Check done.
     *
     * @return void
     */
    public function isDone()
    {
        return $this->status == self::STATUS_DONE;
    }

    /**
     * Check rejected.
     *
     * @return void
     */
    public function isRejected()
    {
        return $this->status == self::STATUS_REJECTED;
    }

    /**
     * Get billing info.
     *
     * @return void
     */
    public function getBillingInfo()
    {
        switch ($this->type) {
            case self::TYPE_RENEW_SUBSCRIPTION:
                $subscription = Subscription::findByUid($this->getMetadata()['subscription_uid']);
                $chargeInfo = trans('messages.bill.charge_before', [
                    'date' => $this->customer->formatDateTime($subscription->current_period_ends_at, 'date_full'),
                ]);
                $plan = $subscription->plan;
                break;
            case self::TYPE_NEW_SUBSCRIPTION:
                $subscription = Subscription::findByUid($this->getMetadata()['subscription_uid']);
                $chargeInfo = trans('messages.bill.charge_now');
                $plan = $subscription->plan;
                break;
            case self::TYPE_CHANGE_PLAN:
                $data = $this->getMetadata();
                $plan = \Acelle\Model\Plan::findByUid($data['new_plan_uid']);
                $chargeInfo = trans('messages.bill.charge_now');
                break;
            default:
                $chargeInfo = '';
        }

        return  [
            'title' => $this->title,
            'description' => $this->description,
            'bill' => $this->invoiceItems()->get()->map(function ($item) {
                return [
                    'title' => $item->title,
                    'description' => $item->description,
                    'price' => format_price($item->amount, $item->invoice->currency->format),
                    'tax' => format_price($item->getTax(), $item->invoice->currency->format),
                    'tax_p' => number_with_delimiter($item->getTaxPercent()),
                    'discount' => format_price($item->discount, $item->invoice->currency->format),
                    'sub_total' => format_price($item->subTotal(), $item->invoice->currency->format),
                ];
            }),
            'charge_info' => $chargeInfo,
            'total' => format_price($this->total(), $this->currency->format),
            'sub_total' => format_price($this->subTotal(), $this->currency->format),
            'tax' => format_price($this->getTax(), $this->currency->format),
            'pending' => $this->getPendingTransaction(),
            'invoice_uid' => $this->uid,
            'due_date' => $this->created_at,
            'type' => $this->type,
            'plan' => $plan,
            'fee' => $this->fee ? format_price($this->fee, $this->currency->format) : null,
            'billing_first_name' => $this->billing_first_name,
            'billing_last_name' => $this->billing_last_name,
            'billing_address' => $this->billing_address,
            'billing_country' => $this->billing_country_id ? \Acelle\Model\Country::find($this->billing_country_id)->name : '',
            'billing_email' => $this->billing_email,
            'billing_phone' => $this->billing_phone,
        ];
    }

    /**
     * Add transactions.
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
     * Check is renew subscription invoice.
     *
     * @return boolean
     */
    public function isRenewSubscriptionInvoice()
    {
        return $this->type == self::TYPE_RENEW_SUBSCRIPTION;
    }

    /**
     * Check is change plan invoice.
     *
     * @return boolean
     */
    public function isChangePlanInvoice()
    {
        return $this->type == self::TYPE_CHANGE_PLAN;
    }

    /**
     * Add transaction.
     *
     * @return array
     */
    public function createPendingTransaction($gateway)
    {
        if ($this->getPendingTransaction()) {
            throw new \Exception('Invoice already has a pending transaction!');
        }

        // @todo: dung transactions()->new....
        $transaction = new Transaction();
        $transaction->invoice_id = $this->id;
        $transaction->status = Transaction::STATUS_PENDING;
        $transaction->allow_manual_review = $gateway->allowManualReviewingOfTransaction();

        // This information is needed for verifying a transaction status later on
        $transaction->method = $gateway->getType();

        $transaction->save();

        return $transaction;
    }

    public function isUnpaid()
    {
        return in_array($this->status, [
            self::STATUS_NEW,
        ]);
    }

    /**
     * Checkout.
     *
     * @return array
     */
    public function checkout($gateway, $payCallback)
    {
        $invoice = $this;
        // \DB::transaction(function() use ($gateway, $invoice) {
        $invoice->createPendingTransaction($gateway);

        try {
            $result = $payCallback($invoice);

            if ($result->isDone()) {
                // Stripe, PayPal, Braintree for example
                $invoice->fulfill();
            } elseif ($result->isFailed()) {
                // Stripe, PayPal, Braintree for example
                $invoice->payFailed($result->error);
            } elseif ($result->isStillPending()) {
                // Coin, offline shouls return this status
                // Wait more, check again later....
                // Coinpayment, offline
            } elseif ($result->isVerificationNotNeeded()) {
                // IMPORTANT: this special status is used for checking (pending) transaction status only
                //          **** SERVICES SHOULD NOT RETURN THIS STATUS IN CHECKOUT method ****
                // Do nothing, just wait for the service to finish it itself (Stripe)
                // Service should not return this status, it is used for verification only
            }
        } catch (\Exception $e) {
            // pay failed
            $invoice->payFailed($e->getMessage());
        }
    }

    public function isFree()
    {
        return $this->total() == 0;
    }

    public function confirmWithoutPayment()
    {
        $this->fulfill();
    }

    public function cancel()
    {
        $this->cancelProcess();

        // delete invoice
        $this->delete();
    }

    public function cancelProcess()
    {
        $data = $this->getMetadata();
        $subscription = Subscription::findByUid($data['subscription_uid']);

        switch ($this->type) {
            case self::TYPE_NEW_SUBSCRIPTION:

                $subscription->abortNew();
                break;
            case self::TYPE_RENEW_SUBSCRIPTION:
                // do nothing
                break;
            case self::TYPE_CHANGE_PLAN:
                // do nothing
                break;
            default:
                throw new \Exception('Invoice type is not valid: ' . $this->type);
        }
    }

    public function updateBillingInformation($billing)
    {
        $validator = \Validator::make($billing, [
            'billing_first_name' => 'required',
            'billing_last_name' => 'required',
            'billing_address' => 'required',
            'billing_country_id' => 'required',
            'billing_email' => 'required|email',
            'billing_phone' => 'required',
        ]);

        if ($validator->fails()) {
            return $validator;
        }

        $this->fill($billing);
        $this->save();

        return $validator;
    }

    public function getBillingName()
    {
        return $this->billing_first_name . ' ' . $this->billing_last_name;
    }

    public static function createInvoice($options)
    {
        // create invoice
        $invoice = new self();
        $invoice->status = self::STATUS_NEW;
        $invoice->type = $options['status'];


        $invoice->title = $options['title'];
        $invoice->description = $options['description'];
        $invoice->customer_id = $options['customer_id'];
        $invoice->currency_id = $options['currency_id'];

        // default billing info
        // $billingAddress = $this->customer->getDefaultBillingAddress();
        if ($options['billingAddress']) {
            $invoice->billing_first_name = $options['billingAddress']->first_name;
            $invoice->billing_last_name = $options['billingAddress']->last_name;
            $invoice->billing_address = $options['billingAddress']->address;
            $invoice->billing_email = $options['billingAddress']->email;
            $invoice->billing_phone = $options['billingAddress']->phone;
            $invoice->billing_country_id = $options['billingAddress']->country_id;
        }

        // save
        $invoice->save();

        // add invoice number
        $invoice->createInvoiceNumber();

        // data
        $invoice->updateMetadata($options['metadata']);

        // add item
        foreach ($options['items'] as $item) {
            $invoiceItem = $invoice->invoiceItems()->create([
                'item_id' => $item['id'],
                'item_type' => $item['type'],
                'amount' => $item['amount'],
                'title' => $item['title'],
                'description' => $item['description'],
            ]);
        }

        return $invoice;
    }

    public function updatePaymentServiceFee($gateway)
    {
        $this->fee = $gateway->getMinimumChargeAmount($this->currency->code);
        $this->save();
    }

    public function hasBillingInformation()
    {
        if (empty($this->billing_first_name) ||
            empty($this->billing_last_name) ||
            empty($this->billing_phone) ||
            empty($this->billing_address) ||
            empty($this->billing_country_id) ||
            empty($this->billing_email)
        ) {
            return false;
        }

        return true;
    }

    public static function getTemplateContent()
    {
        if (\Acelle\Model\Setting::get('invoice.custom_template')) {
            return \Acelle\Model\Setting::get('invoice.custom_template');
        } else {
            return view('invoices.template');
        }
    }

    public function getInvoiceHtml()
    {
        $content = self::getTemplateContent();
        $bill = $this->getBillingInfo();

        // transalte tags
        $values = [
            ['tag' => '{COMPANY_NAME}', 'value' => \Acelle\Model\Setting::get('company_name')],
            ['tag' => '{COMPANY_ADDRESS}', 'value' => \Acelle\Model\Setting::get('company_address')],
            ['tag' => '{COMPANY_EMAIL}', 'value' => \Acelle\Model\Setting::get('company_email')],
            ['tag' => '{COMPANY_PHONE}', 'value' => \Acelle\Model\Setting::get('company_phone')],
            ['tag' => '{FIRST_NAME}', 'value' => $bill['billing_first_name']],
            ['tag' => '{LAST_NAME}', 'value' => $bill['billing_last_name']],
            ['tag' => '{ADDRESS}', 'value' => $bill['billing_address']],
            ['tag' => '{COUNTRY}', 'value' => $bill['billing_country']],
            ['tag' => '{EMAIL}', 'value' => $bill['billing_email']],
            ['tag' => '{PHONE}', 'value' => $bill['billing_phone']],
            ['tag' => '{INVOICE_NUMBER}', 'value' => $this->number],
            ['tag' => '{CURRENT_DATETIME}', 'value' => $this->customer->formatCurrentDateTime('date_full')],
            ['tag' => '{INVOICE_DUE_DATE}', 'value' => $this->customer->formatDateTime($bill['due_date'], 'date_full')],
            ['tag' => '{ITEMS}', 'value' => view('invoices._template_items', [
                'bill' => $bill,
                'invoice' => $this,
            ])],
        ];

        foreach ($values as $value) {
            $content = str_replace($value['tag'], $value['value'], $content);
        }

        $content = StringHelper::transformUrls($content, function ($url, $element) {
            if (strpos($url, '#') === 0) {
                return $url;
            }

            if (strpos($url, 'mailto:') === 0) {
                return $url;
            }

            if (parse_url($url, PHP_URL_HOST) === false) {
                // false ==> if url is invalid
                // null ==> if url does not have host information
                return $url;
            }

            if (StringHelper::isTag($url)) {
                return $url;
            }

            if (strpos($url, '/') === 0) {
                // absolute url with leading slash (/) like "/hello/world"

                return join_url(getAppHost(), $url);
            } elseif (strpos($url, 'data:') === 0) {
                // base64 image. Like: "data:image/png;base64,iVBOR"
                return $url;
            } else {
                return $url;
            }
        });

        return $content;
    }

    public function exportToPdf()
    {
        // instantiate and use the dompdf class
        $dompdf = new Dompdf(array('enable_remote' => true));
        $content = mb_convert_encoding($this->getInvoiceHtml(), 'HTML-ENTITIES', 'UTF-8');
        $dompdf->loadHtml($content);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4');

        // Render the HTML as PDF
        $dompdf->render();

        return $dompdf->output();
    }

    public static function getTags()
    {
        $tags = [
            ['name' => '{COMPANY_NAME}', 'required' => false],
            ['name' => '{COMPANY_ADDRESS}', 'required' => false],
            ['name' => '{COMPANY_EMAIL}', 'required' => false],
            ['name' => '{COMPANY_PHONE}', 'required' => false],
            ['name' => '{FIRST_NAME}', 'required' => false],
            ['name' => '{LAST_NAME}', 'required' => false],
            ['name' => '{ADDRESS}', 'required' => false],
            ['name' => '{COUNTRY}', 'required' => false],
            ['name' => '{EMAIL}', 'required' => false],
            ['name' => '{PHONE}', 'required' => false],
            ['name' => '{INVOICE_NUMBER}', 'required' => false],
            ['name' => '{CURRENT_DATETIME}', 'required' => false],
            ['name' => '{INVOICE_DUE_DATE}', 'required' => false],
            ['name' => '{ITEMS}', 'required' => false],
            ['name' => '{CUSTOMER_ADDRESS}', 'required' => false],
        ];

        return $tags;
    }

    public function createInvoiceNumber()
    {
        if (\Acelle\Model\Setting::get('invoice.current')) {
            $currentNumber = intval(\Acelle\Model\Setting::get('invoice.current'));
        } else {
            $currentNumber = 1;
        }

        $this->number = sprintf(\Acelle\Model\Setting::get('invoice.format'), $currentNumber);
        $this->save();

        // update current number
        \Acelle\Model\Setting::set('invoice.current', ($currentNumber + 1));
    }
}
