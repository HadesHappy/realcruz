<?php

/**
 * UserActivation class.
 *
 * Model class for user activation
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

class UserActivation extends Model
{
    /**
     * Get user activation token.
     *
     * @return string
     */
    public static function getToken()
    {
        return hash_hmac('sha256', str_random(40), config('app.key'));
    }

    /**
     * User.
     *
     * @return string
     */
    public function user()
    {
        return $this->belongsTo('Acelle\Model\User');
    }
}
