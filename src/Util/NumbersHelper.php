<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 05/12/17
 * Time: 22:15
 */

namespace Devguar\OContainer\Util;


class NumbersHelper
{
    public static function convertVisualToDatabase($number){
        $number = (float) str_replace(',','.',$number);
        return $number;
    }

    public static function convertDatabaseToVisual($number,$decimals = 2){
        $number = number_format($number, $decimals,",",".");
        return $number;
    }
}