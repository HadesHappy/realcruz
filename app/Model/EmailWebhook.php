<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Acelle\Library\Traits\HasUid;

class EmailWebhook extends Model
{
    use HasFactory;
    use HasUid;

    public const TYPE_OPEN = 'open';
    public const TYPE_CLICK = 'click';
    public const TYPE_UNSUBSCRIBE = 'unsubscribe';

    public function emailLink()
    {
        return $this->belongsTo('Acelle\Model\EmailLink');
    }

    public function email()
    {
        return $this->belongsTo('Acelle\Model\Email');
    }

    public function createFromArray($params)
    {
        // init rule
        $rules = [
            'endpoint' => 'required|url',
        ];

        // fill
        $this->type = $params['type'];
        $this->endpoint = $params['endpoint'];

        if ($params['type'] == self::TYPE_CLICK) {
            // fill email link id
            if (isset($params['email_link_id'])) {
                $this->email_link_id = $params['email_link_id'];
            }

            // rule update
            $rules['email_link_id'] = 'required';
        }

        // make validator
        $validator = \Validator::make($params, $rules);

        // redirect if fails
        if ($validator->fails()) {
            return [$this, $validator];
        }

        $this->save();

        return [$this, $validator];
    }

    public function updateFromArray($params)
    {
        // init rule
        $rules = [
            'endpoint' => 'required|url',
        ];

        // fill
        $this->endpoint = $params['endpoint'];

        // make validator
        $validator = \Validator::make($params, $rules);

        // redirect if fails
        if ($validator->fails()) {
            return [$this, $validator];
        }

        $this->save();

        return [$this, $validator];
    }
}
