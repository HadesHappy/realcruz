<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Acelle\Model\AutoTrigger as AutoTriggerModel;
use Acelle\Model\DeliveryAttempt;
use Acelle\Model\Email;
use Exception;

class AutoTrigger extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $trigger = AutoTriggerModel::find($request->id);
        $info = [];
        $info[] = sprintf('This is an auto trigger for automation {{ %s }}', $trigger->automation2->name);
        $info[] = sprintf('Subscriber {{ %s }}', $trigger->subscriber->email);

        $actions = [];
        $trigger->getActions(function ($a) use (&$actions, $trigger) {
            $description = "+ [". (($a->getLastExecuted()) ? "Executed" : "Waiting") . "] " .  $a->getId() . ": " . $a->getTitle();
            if ($a->isCondition()) {
                $description .= " (". $a->getEvaluationResult() .")";
            }

            if ($a->getType() == 'ElementAction' && $a->getLastExecuted()) {
                // Attempt
                $email = Email::findByUid($a->getOption('email_uid'));

                $attempt = DeliveryAttempt::where('email_id', $email->id)->where('auto_trigger_id', $trigger->id)->first();

                $id = $attempt->id;
                $description .= " (Attempt: ".$id.", ";
            }

            $actions[] = $description;
        });

        $info[] = implode('<br>', $actions);

        echo implode('<br>', $info);
    }

    public function check(Request $request)
    {
        $trigger = AutoTriggerModel::find($request->id);

        // Execute AutoTrigger#check
        // Notice that calling check() directly against AutoTrigger will not update automation's lastError
        $trigger->check();
        echo "Done";
    }
}
