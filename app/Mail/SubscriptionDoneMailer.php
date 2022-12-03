<?php

namespace Acelle\Mail;

use Acelle\Model\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionDoneMailer extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * The subscription instance.
     *
     * @var Order
     */
    protected $subscription;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(trans('messages.subscription_done.email_subject'))
            ->view('subscription.email.subscription_done_' . \Acelle\Model\Setting::get('system.payment_gateway'))
            ->with([
                'customerName' => $this->subscription->user->displayName(),
                'planName' => $this->subscription->plan->name,
                'link' => action('SubscriptionController@index'),
            ]);
    }
}
