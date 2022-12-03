<?php

namespace Acelle\Library\Automation;

use Acelle\Model\MailList;
use Exception;

class Operate extends Action
{
    public const OPERATION_TAG = 'tag';
    public const OPERATION_COPY = 'copy';
    public const OPERATION_MOVE = 'move';

    /*****

        Operate action may result in the following cases:
          + Done OK
          + Exception (any type of exception...)
        In case of Exception, it is better to stop the whole automation process and write error log to the automation
        so that the responsible person can check it

        Then, "last_executed" is used as a flag indicating that the process is done
        Execution always returns TRUE

    ****/
    protected function doExecute()
    {
        if (config('app.demo') == 'true') {
            return true;
        }

        $operationType = $this->getOption('operation_type');
        $subscriber = $this->autoTrigger->subscriber;

        if ($operationType == self::OPERATION_TAG) {
            $tags = $this->getOption('tags');
            $subscriber->updateTags($tags, $merge = true);
            $this->logger->info(sprintf('* Tag contact "%s" with: %s', $subscriber->email, implode(', ', $tags)));
        } elseif ($operationType == self::OPERATION_COPY || $operationType == self::OPERATION_MOVE) {
            $toListUid = $this->getOption('target_list_uid');

            if (is_null($toListUid)) {
                throw new Exception("Cannot copy/move contact, target list not set");
            }

            $toList = MailList::findByUid($toListUid);

            if (is_null($toList)) {
                throw new Exception("Cannot copy/move contact, target list does not exist: {$toListUid}");
            }

            $duplicateCallback = function ($subscriber_) use ($operationType, $toList) {
                $this->logger->info(sprintf('Notice: skip %s contact "%s" to list "%s". Duplicate email', $operationType, $subscriber_->email, $toList->name));
            };

            // @TODO: use copy instead of move
            $subscriber->copy($toList, $duplicateCallback);

            $this->logger->info(sprintf('DONE: %s contact "%s" to list "%s"', $operationType, $subscriber->email, $toList->name));
        } else {
            throw new Exception("Unknown operation type: {$operationType}");
        }

        // Return true
        return true;
    }

    // Overwrite
    public function getActionDescription()
    {
        $nameOrEmail = $this->autoTrigger->subscriber->getFullNameOrEmail();

        return sprintf('Perform an operation');
    }

    public function getProgressDescription()
    {
        $subscriber = $this->autoTrigger->subscriber;
        $operationType = $this->getOption('operation_type');

        if ($operationType == self::OPERATION_TAG) {
            $tags = $this->getOption('tags');

            return sprintf('* Tag contact "%s" with: %s', $subscriber->email, implode(', ', $tags));
        } elseif ($operationType == self::OPERATION_COPY || $operationType == self::OPERATION_MOVE) {
            $toListUid = $this->getOption('target_list_uid');

            if (is_null($toListUid)) {
                throw new Exception("Cannot get trigger info contact, target list not set");
            }

            $toList = MailList::findByUid($toListUid);

            if (is_null($toList)) {
                throw new Exception("Cannot get trigger information, list does not exist: {$toListUid}");
            }

            return sprintf('* Action: %s contact "%s" to list "%s"', $operationType, $subscriber->email, $toList->name);
        } else {
            throw new Exception("Unknown operation type: {$operationType}");
        }
    }
}
