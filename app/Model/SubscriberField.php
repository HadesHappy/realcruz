<?php

/**
 * SubscriberField class.
 *
 * Model class for subscriber custom fields
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
use Acelle\Library\Traits\QueryHelper;

class SubscriberField extends Model
{
    use QueryHelper;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'field_id', 'subscriber_id', 'value',
    ];

    /**
     * Associations.
     *
     * @var object | collect
     */
    public function field()
    {
        return $this->belongsTo('Acelle\Model\Field');
    }
}
