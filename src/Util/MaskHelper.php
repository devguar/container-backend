<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 15/11/17
 * Time: 22:38
 */

namespace Devguar\OContainer\Util;


class MaskHelper
{
    public static function onlyNumbers($value){
        return preg_replace( '/[^0-9]/', '', $value );
    }
}