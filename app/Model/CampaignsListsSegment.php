<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;

class CampaignsListsSegment extends Model
{
    /**
     * Associations.
     *
     * @var object | collect
     */
    public function campaign()
    {
        return $this->belongsTo('Acelle\Model\Campaign');
    }

    public function mailList()
    {
        return $this->belongsTo('Acelle\Model\MailList');
    }

    public function segment()
    {
        return $this->belongsTo('Acelle\Model\Segment');
    }
}
