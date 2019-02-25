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

    public static function daysOfWeekDescription(){
        $dias = ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'];
        return $dias;
    }

    //Converte 14/06/2017 em Datetime Object
    public static function convertVisualToDatetime($dateVisual){
        if ($dateVisual == null) return null;

        $datetime = new \DateTime();
        $datetime = $datetime->createFromFormat('d/m/Y', $dateVisual);
        return $datetime;
    }

    //Converte 2017-06-14 em Datetime Object
    public static function convertDatabaseToDatetime($dateDatabase){
        if ($dateDatabase == null) return null;

        $datetime = new \DateTime();
        $datetime = $datetime->createFromFormat('Y-m-d', $dateDatabase);
        return $datetime;
    }

    //Converte 14/06/2017 em 2017-06-14
    public static function convertVisualToDatabase($dateVisual){
        if ($dateVisual == null) return null;

        $datetime = self::convertVisualToDatetime($dateVisual);
        return $datetime->format('Y-m-d');
    }

    //Converte 2017-06-14 em 14/06/2017
    public static function convertDatabaseToVisual($dateDatabase){
        if ($dateDatabase == null) return null;

        $datetime = self::convertDatabaseToDatetime($dateDatabase);
        return $datetime->format("d/m/Y");
    }

    //Converte Datetime Object em 14/06/2017
    public static function convertDatetimeToVisual(\DateTime $dateTime){
        if ($dateTime == null) return null;

        return $dateTime->format("d/m/Y");
    }

    //Converte Datetime Object em 2017-06-14
    public static function convertDatetimeToDatabase(\DateTime $dateTime){
        if ($dateTime == null) return null;

        return $dateTime->format('Y-m-d');
    }

    public static function firstDayOfMonth(\DateTime $dateTime){
        return $dateTime->modify('first day of this month');
    }

    public static function lastDayOfMonth(\DateTime $dateTime){
        return $dateTime->modify('last day of this month');
    }

}