<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 04/06/17
 * Time: 12:40
 */

namespace Devguar\OContainer\Util;


class DateAndTimeHelper
{
    public static function monthName($month){
        $months = array('Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro');
        return $months[$month - 1];
    }

    public static function dateDescription(\DateTime $date){
        $day = $date->format('d');
        $month = $date->format('m');
        $year = $date->format('Y');

        return $day.' de '.self::monthName($month).' de '.$year;
    }
}