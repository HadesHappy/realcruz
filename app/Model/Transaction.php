<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Acelle\Library\Facades\Billing;
use Acelle\Library\Traits\HasUid;

class Transaction extends Model
{
    use HasUid;

    // wait status
    public const STATUS_PENDING = 'pending';
    public const STATUS_FAILED = 'failed';
    public const STATUS_SUCCESS = 'success';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'error', 'status'
    ];

    /**
     * Invoice.
     */
    public function invoice()
    {
        return $this->belongsTo('Acelle\Model\Invoice');
    }

    /**
     * Is failed.
     */
    public function isFailed()
    {
        return $this->status == self::STATUS_FAILED;
    }

    /**
     * Set failed.
     */
    public function setFailed($error=null)
    {
        $this->status = self::STATUS_FAILED;
        $this->error = $error;
        $this->save();
    }

    /**
     * Set as success.
     */
    public function setSuccess()
    {
        $this->status = self::STATUS_SUCCESS;
        $this->save();
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

    /**
     * Check .
     *
     * @var object | collect
     */
    public function check()
    {
        if (!Billing::isGatewayTypeRegistered($this->method)) {
            $this->invoice->payFailed(sprintf('Cannot verify this traction because it was previously processed by gateway type %s but the gateway is no longer available', $this->method));
        }

        $invoice = $this->invoice;

        // Retrieve the gateway that was previously used for processing this tranction/invoice
        $gateway = Billing::getGateway($this->method);

        try {
            $result = $gateway->verify($this);

            if ($result->isDone()) {
                // Coin only
                $invoice->fulfill();
            } elseif ($result->isFailed()) {
                // Coin only
                $invoice->payFailed($result->error);
            } elseif ($result->isStillPending()) {
                // Wait more, check again later.... Coin, Offline
            } elseif ($result->isVerificationNotNeeded()) {
                // Do nothing, just wait for the service to finish it itself (Stripe)
            }

            return $result;
        } catch (\Exception $e) {
            $invoice->payFailed($e->getMessage());
        }
    }

    public function allowManualReview()
    {
        return $this->allow_manual_review;
    }
}
