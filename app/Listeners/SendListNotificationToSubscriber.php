<?php

namespace Acelle\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Acelle\Events\MailListSubscription;
use Acelle\Events\MailListUnsubscription;
use Acelle\Model\Setting;

class SendListNotificationToSubscriber
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MailListSubscription  $event
     * @return void
     */
    public function handleMailListSubscription(MailListSubscription $event)
    {
        $subscriber = $event->subscriber;
        $list = $subscriber->mailList;

        if ($list->send_welcome_email) {
            $list->sendSubscriptionWelcomeEmail($subscriber);
        }
    }

    /**
     * Handle the event.
     *
     * @param  MailListSubscription  $event
     * @return void
     */
    public function handleMailListUnsubscription(MailListUnsubscription $event)
    {
        $subscriber = $event->subscriber;
        $list = $subscriber->mailList;

        if ($list->unsubscribe_notification) {
            $list->sendUnsubscriptionNotificationEmail($subscriber);
        }
    }

    /**
     * Handle the event.
     *
     * @param  AdminLoggedIn  $event
     * @return void
     */
    public function subscribe($events)
    {
        $events->listen(
            'Acelle\Events\MailListSubscription',
            [SendListNotificationToSubscriber::class, 'handleMailListSubscription']
        );

        $events->listen(
            'Acelle\Events\MailListUnsubscription',
            [SendListNotificationToSubscriber::class, 'handleMailListUnsubscription']
        );
    }
}
