<?php

namespace Acelle\Listeners;

use Acelle\Events\MailListImported;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Acelle\Model\Automation2;
use Acelle\Model\Setting;

class TriggerAutomationForImportedContacts
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
     * @param  MailListImported  $event
     * @return void
     */
    public function handle(MailListImported $event)
    {
        $trigger = Setting::isYes('automation.trigger_imported_contacts');

        $automations = $event->list->automations;
        foreach ($automations as $auto) {
            if ($auto->getTriggerType() != Automation2::TRIGGER_TYPE_WELCOME_NEW_SUBSCRIBER) {
                continue;
            }

            if (!$trigger) {
                $auto->logger()->warning("Do not trigger automation for imported contacts");
                continue;
            }

            if (!$auto->isActive()) {
                $auto->logger()->warning("Automation is INACTIVE");
                continue;
            }

            $auto->triggerImportedContacts();
        };
    }
}
