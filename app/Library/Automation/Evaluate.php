<?php

namespace Acelle\Library\Automation;

use Acelle\Model\Email;
use Acelle\Model\EmailLink;
use Illuminate\Support\Carbon;

class Evaluate extends Action
{
    /*****

        Evaluate action may result in the following cases:
          + Check OK (result is true or false, no care)
          + Exception (email UID not found for example, other exception...)
        In case of Exception, it is better to stop the whole automation process and write error log to the automation
        so that the responsible person can check it

        Then, "last_executed" is used as a flag indicating that the process is done
        Execution always returns TRUE

    ****/

    protected $childYes;
    protected $childNo;

    public function __construct($params = [])
    {
        parent::__construct($params);

        $this->childYes = array_key_exists('childYes', $params) ? $params['childYes'] : null;
        $this->childNo = array_key_exists('childNo', $params) ? $params['childNo'] : null;
    }

    public function toJson()
    {
        $json = parent::toJson();
        $json = array_merge($json, [
            'childYes' => $this->childYes,
            'childNo' => $this->childNo,
        ]);

        return $json;
    }

    protected function doExecute()
    {
        // IMPORTANT
        // If this is the latest also the last action of the workflow
        // no more execute, just return true
        // UPDATE: check always, wait for open/click anyway! if it is the last action
        // if (!is_null($this->last_executed)) {
        //     $this->logger->info('Latest also last action');
        //     return true;
        // }

        if (config('app.demo') == 'true') {
            $this->evaluationResult = (bool) random_int(0, 1);
        } else {
            $this->evaluationResult = $this->evaluateCondition();
        }

        // In condition is met, proceed immediately
        if ($this->evaluationResult) {
            return true;
        }

        // In case condition is not met,
        // double check for wait time before proceeding
        $now = Carbon::now();
        $waitDuration = $this->getOption('wait');  // 1 hour, 1 day, 2 days
        $parentExecutionTime = Carbon::createFromTimestamp($this->getParent()->getLastExecuted());
        $due = $parentExecutionTime->modify($waitDuration);

        $check = $now->gte($due);

        if ($check) {
            sleep(1); // to avoid same day with previous action when modifying (n days)
            $this->logger->info(sprintf('---> It is already %s minutes (or %s hours) due! proceed next with NO branch', $now->diffInMinutes($due), $now->diffInHours($due)));

            return true;
        } else {
            $this->logger->info(sprintf('Wait for another %s minutes (or %s hours) for condition to be met', $now->diffInMinutes($due), $now->diffInHours($due)));
            return false;
        }
    }

    public function evaluateCondition()
    {
        $criterion = $this->getOption('type');
        $result = null;

        switch ($criterion) {
            case 'open':
                if (empty($this->getOption('email'))) {
                    throw new \Exception('Email missing for open condition');
                }
                $result = $this->evaluateEmailOpenCondition();
                break;
            case 'click':
                if (empty($this->getOption('email_link'))) {
                    throw new \Exception('URL missing for click condition');
                }
                $result = $this->evaluateEmailClickCondition();
                break;
            default:
                # code...
                break;
        }

        return $result;
    }

    public function evaluateEmailOpenCondition()
    {
        $emailUid = $this->getOption('email');
        $email = Email::findByUid($emailUid);

        return $email->isOpened($this->autoTrigger->subscriber);
    }

    public function evaluateEmailClickCondition()
    {
        $linkUid = $this->getOption('email_link');
        $email = EmailLink::findByUid($linkUid)->email;

        return $email->isClicked($this->autoTrigger->subscriber);
    }

    public function getActionDescription()
    {
        $nameOrEmail = $this->autoTrigger->subscriber->getFullNameOrEmail();
        $options = $this->getOptions();

        if (array_key_exists('email', $options)) {
            $emailUid = $this->getOption('email');
            $email = Email::findByUid($emailUid);
        } else {
            $linkUid = $this->getOption('email_link');
            $link = EmailLink::findByUid($linkUid);
        }

        if ($this->getOption('type') == 'open') {
            return sprintf('Tracking: waiting for user %s to READ email entitled "%s"', $nameOrEmail, $email->subject);
        } else {
            return sprintf('Tracking: waiting for user %s to CLICK link "%s"', $nameOrEmail, $link->link);
        }
    }

    public function getProgressDescription()
    {
        if (is_null($this->getLastExecuted())) {
            if ($this->getOption('type') == 'open') {
                return "* Check if email is opened";
            } else {
                return "* Check if link is clicked";
            }
        }

        if ($this->getOption('type') == 'open') {
            $emailUid = $this->getOption('email');
            $email = Email::findByUid($emailUid);
            if ($this->evaluationResult == 'true') {
                return sprintf('Opened email "%s"', $email->subject);
            } else {
                return sprintf('Did not open email "%s"', $email->subject);
            }
        } else {
            $linkUid = $this->getOption('email_link');
            $link = EmailLink::findByUid($linkUid);

            if ($this->evaluationResult == 'true') {
                return sprintf('Clicked: "%s"', $link->link);
            } else {
                return sprintf('Did not click: "%s"', $link->link);
            }
        }
    }

    public function hasChild($e)
    {
        if (is_null($this->childYes) && is_null($this->childNo)) {
            return false;
        }

        return $e->getId() == $this->childYes || $e->getId() == $this->childNo;
    }

    public function getNextActionId()
    {
        // IMPORTANT: if action is not yet executed, return NULL
        // IMPORTANT: evaluationResult has 3 (not 2) possible values: NULL | YES | NO
        // IMPORTANT: use is_null instead of "== null" because [false == null]
        if (is_null($this->evaluationResult)) {
            return null;
        } elseif ($this->evaluationResult) {
            return $this->childYes;
        } else {
            return $this->childNo;
        }
    }

    public function getChildYesId()
    {
        return $this->childYes;
    }

    public function getChildNoId()
    {
        return $this->childNo;
    }

    public function isCondition()
    {
        return true;
    }

    public function getEvaluationResult()
    {
        return $this->evaluationResult;
    }
}
