<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warmup extends Model
{
    use HasFactory;
    public static function days()
    {
        $days = array();
        $days[] = array('count'=>'20');
        $days[] = array('count'=>'40');
        $days[] = array('count'=>'70');
        $days[] = array('count'=>'100');
        $days[] = array('count'=>'150');
        $days[] = array('count'=>'250');
        $days[] = array('count'=>'300');
        $days[] = array('count'=>'20');
        $days[] = array('count'=>'40');
        $days[] = array('count'=>'70');
        $days[] = array('count'=>'100');
        $days[] = array('count'=>'150');
        $days[] = array('count'=>'250');
        $days[] = array('count'=>'300');
        $days[] = array('count'=>'20');
        $days[] = array('count'=>'40');
        $days[] = array('count'=>'70');
        $days[] = array('count'=>'100');
        $days[] = array('count'=>'150');
        $days[] = array('count'=>'250');
        $days[] = array('count'=>'300');
        $days[] = array('count'=>'20');
        $days[] = array('count'=>'40');
        $days[] = array('count'=>'70');
        $days[] = array('count'=>'100');
        $days[] = array('count'=>'150');
        $days[] = array('count'=>'250');
        $days[] = array('count'=>'300');
        $days[] = array('count'=>'20');
        $days[] = array('count'=>'40');
        $days[] = array('count'=>'70');
        $days[] = array('count'=>'100');
        $days[] = array('count'=>'150');
        $days[] = array('count'=>'250');
        $days[] = array('count'=>'300');
        return $days;
    }
}