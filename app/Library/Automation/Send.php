<?php

namespace Acelle\Library\Automation;

use Acelle\Model\Email;
use Exception;

class Send extends Action
{
    /*****

        Send action may result in the following cases:
          + Send OK (email queued, do not care about delivery status)
          + Exception (email UID not found for example, other exception...)
        In case of Exception, it is better to stop the whole automation process and write error log to the automation
        so that the responsible person can check it

        Then, "last_executed" is used as a flag indicating that the process is done
        Execution always returns TRUE

    ****/

    protected function doExecute()
    {
        /*** do not care if subscriber is active() or not ***/
        /*
        if (!$subscriber->isActive()) {
            $this->logger->warning(sprintf('Subscriber "%s" is not active (current status: "%s")', $subscriber->email, $subscriber->status));

            return false;
        }
        */

        if ($this->options['init'] == 'false' || $this->options['init'] == false) {
            throw new Exception("Email not set up yet");
        }

        $email = $this->getEmail();
        $subscriber = $this->autoTrigger->subscriber;

        if (config('app.demo') == 'true') {
            // do not wait
        } else {
            // to avoid same date/time with previous wait, wrong timeline order
            sleep(1);
        }

        // queue, do not send immediately
        $email->queueDeliverTo($this->autoTrigger->subscriber, $this->autoTrigger->id);

        $this->logger->info(sprintf('Send email entitled "%s" to "%s", queued', $email->subject, $this->autoTrigger->subscriber->email));

        return true;
    }

    public function getEmail()
    {
        $email = Email::findByUid($this->options['email_uid']);
        if (is_null($email)) {
            throw new \Exception(sprintf("Cannot find email with UID %s for Action ID %s, title: '%s', AutoTrigger: #%s", $this->options['email_uid'], $this->getId(), $this->getTitle(), $this->autoTrigger->id));
        }
        return $email;
    }

    public function fixInvalidEmailUid()
    {
        $this->setOption('email_uid', null);
        $this->setOption('init', false);
    }

    public function getActionDescription()
    {
        $nameOrEmail = $this->autoTrigger->subscriber->getFullNameOrEmail();

        return sprintf('User %s receives email entitled "%s"', $nameOrEmail, $this->getEmail()->subject);
    }

    public function getProgressDescription()
    {
        $email = $this->getEmail();
        $subscriber = $this->autoTrigger->subscriber;

        if (is_null($this->getLastExecuted())) {
            return sprintf('* Send email "%s" to "%s"', $email->subject, $subscriber->email);
        }

        return sprintf('Sent email "%s" to "%s"', $email->subject, $subscriber->email);
    }
}
