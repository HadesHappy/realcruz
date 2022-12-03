<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Acelle\Library\Traits\HasUid;

class Timeline extends Model
{
    use HasUid;

    protected $fillable = ['automation2_id', 'subscriber_id', 'auto_trigger_id', 'activity', 'activity_type'];

    /**
     * Associations.
     *
     * @var object | collect
     */
    public function subscriber()
    {
        return $this->belongsTo('Acelle\Model\Subscriber');
    }
}
