<?php

/**
 * RouletteWheel class.
 *
 * Implementation of the Roulette Wheel algorithm to pick up a delivery server
 *
 * LICENSE: This product includes software developed at
 * the Acelle Co., Ltd. (http://acellemail.com/).
 *
 * @category   Acelle Library
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

namespace Acelle\Library;

class RouletteWheel
{
    /**
     * Pick a random element from an array based on its probability.
     *
     * @return mixed
     */
    public static function take($a)
    {
        $sum = 0.0;
        $total = array_sum(array_values($a));
        $r = self::frandom();

        // just in case
        if ($r == $sum) { // in other words, r == 0
            return array_keys($a)[0];
        }

        foreach ($a as $key => $percentage) {
            $newsum = $sum + (float) $percentage / (float) $total;
            if ($r > $sum && $r <= $newsum) {
                return $key;
            }
            $sum = $newsum;
        }

        // just in case
        return array_keys($a)[sizeof($a) - 1];
    }

    /**
     * Generate a random float.
     *
     * @return float random
     */
    public static function frandom()
    {
        return (float) rand() / (float) getrandmax();
    }
}
