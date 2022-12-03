<?php

namespace Acelle\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Acelle\Events\MailListSubscription;
use Acelle\Events\MailListUnsubscription;
use Acelle\Model\Setting;

class SendListNotificationToOwner
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
        $user = $list->customer->user;

        if (Setting::isYes('send_notification_email_for_list_subscription')) {
            // Send notification
            $list->sendSubscriptionNotificationEmailToListOwner($subscriber);
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

        if (Setting::isYes('send_notification_email_for_list_subscription')) {
            $list->sendUnsubscriptionNotificationEmailToListOwner($subscriber);
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
            [SendListNotificationToOwner::class, 'handleMailListSubscription']
        );

        $events->listen(
            'Acelle\Events\MailListUnsubscription',
            [SendListNotificationToOwner::class, 'handleMailListUnsubscription']
        );
    }
}
