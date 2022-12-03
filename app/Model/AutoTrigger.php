<?php

/**
 * Automation Event Trigger class.
 *
 * Model class for logging triggered events
 *
 * LICENSE: This product includes software developed at
 * the Acelle Co., Ltd. (http://acellemail.com/).
 *
 * @category   MVC Model
 *
 * @author     N. Pham <n.pham@acellemail.com>
 * @author     L. Pham <l.pham@acellemail.com>
 * @copyright  Acelle Co., Ltd
 * @license    Acelle Co., Ltd
 *
 * @version    1.0
 *
 * @link       http://acellemail.com
 */

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Acelle\Library\Automation\Action;
use Acelle\Library\Automation\Trigger;
use Acelle\Library\Automation\Send;
use Acelle\Library\Automation\Wait;
use Exception;
use Acelle\Library\Traits\HasUid;

class AutoTrigger extends Model
{
    use HasUid;

    protected $fillable = [
        //
    ];

    protected $dates = ['created_at', 'updated_at'];

    public static function boot()
    {
        parent::boot();

        self::saving(function ($model) {
            $model->updateExecutedIndex();
        });

        self::retrieved(function ($model) {
            // IMPORTANT: in case of any change made to the parent automation2
            $model->updateWorkflow();
        });
    }

    /**
     * Associations.
     *
     * @return the associated subscriber
     */
    public function subscriber()
    {
        return $this->belongsTo('Acelle\Model\Subscriber');
    }

    /**
     * Associations.
     *
     * @return the associated automation2
     */
    public function automation2()
    {
        return $this->belongsTo('Acelle\Model\Automation2');
    }

    /**
     * Associations.
     *
     * @return the associated timelines
     */
    public function timelines()
    {
        return $this->hasMany('Acelle\Model\Timeline')->orderBy('created_at', 'DESC');
    }

    // Mark the next action as "latest" and return it
    public function getNextAction($action)
    {
        $nextId = $action->getNextActionId();
        return $this->getActionById($nextId);
    }

    public function recordToTimeline($action)
    {
        $this->timelines()->create([
            'automation2_id' => $this->automation2_id,
            'subscriber_id' => $this->subscriber_id,
            'activity' => $action->getActionDescription(),
            'activity_type' => $action->getType(),
        ]);
    }

    // Notice that this method my be called from an automation or individually
    // As a result, try catch is needed
    public function check()
    {
        $this->logger()->info(sprintf('Trigger [#%s - %s]: Automation [%s] starts checking', $this->id, $this->subscriber->email, $this->automation2->name));

        // STEP #1: Get latest action
        $latest = $this->findLastActionToExecute();

        if ($latest == null) {
            $this->logger()->info(sprintf('Trigger [#%s - %s]: already reached last asction', $this->id, $this->subscriber->email, $this->automation2->name));
            return;
        }

        $this->logger()->info(sprintf('Trigger [#%s - %s]: start from lastest action [%s]', $this->id, $this->subscriber->email, $latest->getTitle()));

        // Save to DB and Record to timeline every time we pass an action
        // IMPORTANT: if action is not saved (with last_executed flag not null), it MIGHT execute again! sending duplicate emails for example
        $passedCallback = function ($action) {
            $this->updateAction($action);
            $this->recordToTimeline($action);
        };

        // * Trigger => always true
        // * Send => always true
        // * Operation => always true
        // * Evaluation => always true
        // * Wait => true or false

        // true means: done with this step, should proceed next while false means stop, wait for next check
        while ($latest->execute($passedCallback) == 'true') {
            $latest = $this->getNextAction($latest);

            if ($latest == null) {
                $this->logger()->info(sprintf('Trigger [#%s - %s]: Reach last action, END!', $this->id, $this->subscriber->email));
                break;
            }

            $this->logger()->info(sprintf('Trigger [#%s - %s]: continue with action [%s]', $this->id, $this->subscriber->email, $latest->getTitle()));
        }

        if (!is_null($latest)) {
            $this->logger()->info(sprintf('Trigger [#%s - %s]: pending at [%s]', $this->id, $this->subscriber->email, $latest->getTitle()));
        }
    }

    public function updateAction($action)
    {
        $json = $this->getJson();
        if (array_key_exists($action->getId(), $json)) {
            $current = $json[$action->getId()];
            $updated = array_merge($current, $action->toJson());
            $json[$action->getId()] = $updated;
        } else {
            $json[$action->getId()] = $action->toJson();
        }

        $this->data = json_encode($json);
        $this->save();
    }

    private function updateExecutedIndex()
    {
        $executed = [];
        $this->getActions(function ($action) use (&$executed) {
            if (!is_null($action->getLastExecuted())) {
                $executed[] = $action->getId();
            }
        });

        $this->executed_index = implode(',', $executed);
    }

    // Get actions in the RIGHT order and with the right branch
    // Use this method instead of #getActions which is for loading the first Trigger only
    public function traverseExecutedPath($callback)
    {
        // Starting point
        $action = $this->getTrigger();

        do {
            if (is_null($action->getLastExecuted())) {
                break;
            }

            $callback($action);
            $action = $this->getNextAction($action);
        } while (!is_null($action));
    }

    public function getExecutedActions()
    {
        $list = [];
        $this->traverseExecutedPath(function ($action) use (&$list) {
            $list[] = $action;
        });

        return $list;
    }

    public function getActions($callback)
    {
        $json = $this->getJson();
        foreach ($json as $key => $attributes) {
            $attributes['id'] = $key;
            $instance = $this->getAction($attributes);
            $instance->setAutoTrigger($this);
            $callback($instance);
        }
    }

    public function getAction($attributes)
    {
        return $this->automation2->getAction($attributes);
    }

    public function updateWorkflow()
    {
        $origins = json_decode($this->automation2->data, true);
        $newJson = [];

        foreach ($origins as $r) {
            $tmp = $this->getAction($r);
            $currentAction = $this->getActionById($tmp->getId());

            if (is_null($currentAction)) {
                $newJson[$tmp->getId()] = $tmp->toJson();
            } else {
                $currentAction->update($r);
                $newJson[$currentAction->getId()] = $currentAction->toJson();
            }
        }

        $this->data = json_encode($newJson);
        $this->save();
    }

    public function getJson()
    {
        return is_null($this->data) ? [] : json_decode($this->data, true);
    }

    // for debugging only
    public function getTrigger()
    {
        $trigger = null;
        $this->getActions(function ($e) use (&$trigger) {
            if ($e->getType() == 'ElementTrigger') {
                $trigger = $e;
            }
        });

        if ($trigger == null) {
            throw new Exception("Automation does not have a trigger!!!!");
        }

        return  $trigger;
    }

    public function logger()
    {
        return $this->automation2->logger();
    }

    public function getActionById($id)
    {
        $selected = null;
        $this->getActions(function ($action) use ($id, &$selected) {
            if ($action->getId() == $id) {
                $selected = $action;
            }
        });

        return $selected;
    }

    public function isActionExecuted($id)
    {
        return !is_null($this->getActionById($id)->getLastExecuted());
    }

    public function isComplete()
    {
        $last = $this->findLastActionToExecute();
        return is_null($last);
    }

    public function findLastActionToExecute()
    {
        $action = $this->getTrigger();

        if (is_null($action->getLastExecuted())) {
            return $action;
        }

        while (!is_null($action->getLastExecuted())) {
            $nextId = $action->getNextActionId();

            if (is_null($nextId)) {
                return null;
            }

            $action = $this->getActionById($nextId);
        }

        return $action;
    }

    public function getLatestAction()
    {
        $action = $this->getTrigger();

        if (is_null($action->getLastExecuted())) {
            return null;
        }

        while (!is_null($action->getLastExecuted())) {
            $nextId = $action->getNextActionId();

            if (is_null($nextId)) {
                return $action;
            }

            $action = $this->getActionById($nextId);
        }

        return $action->getParent();
    }
}
