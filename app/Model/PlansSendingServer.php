<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;

class PlansSendingServer extends Model
{
    public function sendingServer()
    {
        return $this->belongsTo('Acelle\Model\SendingServer');
    }

    /**
     * Show fitness.
     *
     * @var string
     */
    public function showFitness()
    {
        $sum = self::where('plan_id', '=', $this->plan_id)
            ->sum('fitness');

        return round(($this->fitness / $sum) * 100);
    }

    /**
     * Check if is primary.
     *
     * @var bool
     */
    public function isPrimary()
    {
        return $this->is_primary;
    }
}
