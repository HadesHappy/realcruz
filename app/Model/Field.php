<?php

/**
 * Field class.
 *
 * Model class for List's custom fields
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
use Acelle\Library\Traits\HasUid;

class Field extends Model
{
    use HasUid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mail_list_id', 'type', 'label', 'tag', 'default_value', 'visible', 'required',
    ];

    /**
     * Associations.
     *
     * @var object | collect
     */
    public function mailList()
    {
        return $this->belongsTo('Acelle\Model\MailList');
    }

    public function fieldOptions()
    {
        return $this->hasMany('Acelle\Model\FieldOption');
    }

    /**
     * Format string to field tag.
     *
     * @var string
     */
    public static function formatTag($string)
    {
        return strtoupper(preg_replace('/[^0-9a-zA-Z_]/m', '', $string));
    }

    /**
     * Get select options.
     *
     * @return array
     */
    public function getSelectOptions()
    {
        $options = $this->fieldOptions->map(function ($item) {
            return ['value' => $item->value, 'text' => $item->label];
        });

        return $options;
    }

    /**
     * Get control name.
     *
     * @return array
     */
    public static function getControlNameByType($type)
    {
        if ($type == 'date') {
            return 'date';
        } elseif ($type == 'number') {
            return 'number';
        } elseif ($type == 'datetime') {
            return 'datetime';
        }

        return 'text';
    }
}
