<?php

namespace Acelle\Library\Automation;

use Carbon\Carbon;
use Exception;
use Throwable;

abstract class Action
{
    protected $id;
    protected $title;
    protected $type; // ElementCondition (Evaluate), ElementTrigger (Trigger), ElementWait (Wait), ElementAction (Send)
    protected $child;
    protected $options;

    // Own attributes that should be kept
    protected $last_executed = null;
    protected $evaluationResult = null;

    // parent object
    protected $autoTrigger;
    protected $logger;

    public function __construct($params = [])
    {
        $this->id = $params['id'];
        $this->title = $params['title'];
        $this->type = $params['type'];
        $this->child = array_key_exists('child', $params) ? $params['child'] : null;
        $this->options = array_key_exists('options', $params) ? $params['options'] : [];

        $this->last_executed = array_key_exists('last_executed', $params) ? $params['last_executed'] : null;
        $this->evaluationResult = array_key_exists('evaluationResult', $params) ? $params['evaluationResult'] : null;
    }

    public function setAutoTrigger($autoTrigger)
    {
        $this->autoTrigger = $autoTrigger;
        $this->logger = $autoTrigger->logger();
    }

    public function toJson()
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'type' => $this->type,
            'child' => $this->child,
            'options' => $this->getOptions(),
            'last_executed' => $this->getLastExecuted(),
            'evaluationResult' => $this->evaluationResult,
        ];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getLastExecuted()
    {
        return $this->last_executed;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getOption($key)
    {
        return $this->options[$key];
    }

    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    public function getNextActionId()
    {
        return $this->child;
    }

    public function getEvaluationResult()
    {
        return $this->evaluationResult;
    }

    public function recordLastExecutedTime()
    {
        $this->last_executed = Carbon::now()->timestamp;
    }

    public function hasChild($e)
    {
        if (is_null($this->child)) {
            return false;
        }

        return $e->getId() == $this->child;
    }

    // Only use it when an autotrigger is available
    public function getParent()
    {
        if (is_null($this->autoTrigger)) {
            throw new Exception('There is no AutoTrigger associated with this action');
        }

        $parent = null;
        $this->autoTrigger->getActions(function ($action) use (&$parent) {
            if ($action->hasChild($this)) {
                $parent = $action;
            }
        });

        return $parent;
    }

    public function isCondition()
    {
        return false;
    }

    public function update(array $json)
    {
        // Keep the following attributes, do not overwrite
        $keep = [ 'last_executed', 'evaluationResult' ];

        foreach ($json as $key => $value) {
            if (!in_array($key, $keep)) {
                $this->{$key} = $value;
            }
        }
    }

    public function getProgressDescription()
    {
        return null;
    }

    public function getLastExecutedHumanReadable()
    {
        if (is_null($this->getLastExecuted())) {
            return null;
        }

        return Carbon::createFromTimestamp($this->getLastExecuted())->diffForHumans();
    }

    // Template Pattern, execute will in turn call doExecute of inherited classes
    // Just to record the current action information in case of any error
    public function execute($callback)
    {
        // IMPORTANT: this should be placed outside of the try/catch
        // Otherwise, error again in catch block (no trigger information to log)
        if (is_null($this->autoTrigger)) {
            throw new Exception('There is no AutoTrigger associated with this action');
        }

        try {

            // Execute once only
            if (!is_null($this->getLastExecuted())) {
                throw new Exception("Action already executed");
            }

            // Actually execute, implemented by child classes
            $result = $this->doExecute();

            if ($result) {
                // Only record last_executed and execute callback if success
                // IMPORTANT: callback is normally used for saving the current action status
                // IMPORTANT: if action is not saved at this point (with last_executed flag not null),
                //            it MIGHT execute again! sending duplicate emails for example.
                //            Or, in case of Evaluate: it keeps being executed over and over again
                //            and evaluation result may CHANGE over time (email gets clicked/opened)

                $this->recordLastExecutedTime();
                $callback($this);
            }

            return $result;
        } catch (Throwable $t) {
            $msg = sprintf(
                '[%s (Customer UID: %s)] Error executing trigger #%s for %s, at action [#%s %s]: %s',
                $this->autoTrigger->automation2->name,
                $this->autoTrigger->automation2->customer->uid,
                $this->autoTrigger->id,
                $this->autoTrigger->subscriber->email,
                $this->getId(),
                $this->getTitle(),
                $t->getMessage()
            );

            $this->logger->error($msg);

            throw new Exception($msg);
        }
    }
}
