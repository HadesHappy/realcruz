<?php

namespace Acelle\Listeners;

use Acelle\Events\MailListSubscription;
use Acelle\Events\MailListUnsubscription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Acelle\Library\Log as MailLog;

use Acelle\Model\Automation2;

class TriggerAutomation
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
        MailLog::info('[MailListSubscription::TriggerAutomation] triggered');
        $automations = $event->subscriber->mailList->automations;
        $automations = $automations->filter(function ($auto, $key) {
            return $auto->isActive() && (
                $auto->getTriggerType() == Automation2::TRIGGER_TYPE_WELCOME_NEW_SUBSCRIBER
            );
        });

        foreach ($automations as $auto) {
            if (is_null($auto->getAutoTriggerFor($event->subscriber))) {
                $auto->initTrigger($event->subscriber);
            }
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
        MailLog::info('[MailListUnsubscription::TriggerAutomation] triggered');
        $automations = $event->subscriber->mailList->automations;
        $automations = $automations->filter(function ($auto, $key) {
            return $auto->isActive() && (
                $auto->getTriggerType() == Automation2::TRIGGER_TYPE_SAY_GOODBYE_TO_SUBSCRIBER
            );
        });

        foreach ($automations as $auto) {
            if (is_null($auto->getAutoTriggerFor($event->subscriber))) {
                $forceTriggerUnsubscribedContact = true;
                $auto->initTrigger($event->subscriber, $forceTriggerUnsubscribedContact);
            }
        }
    }

    // Subscribe to many events
    public function subscribe($events)
    {
        $events->listen(
            'Acelle\Events\MailListSubscription',
            [TriggerAutomation::class, 'handleMailListSubscription']
        );

        $events->listen(
            'Acelle\Events\MailListUnsubscription',
            [TriggerAutomation::class, 'handleMailListUnsubscription']
        );
    }
}
