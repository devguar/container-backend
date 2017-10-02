<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 02/10/17
 * Time: 12:17
 */

namespace Devguar\OContainer\Util;


class ConvertObjectArrayHelper
{
    /**
     * Convert an array into a stdClass()
     *
     * @param   array   $array  The array we want to convert
     *
     * @return  object
     */
    public static function arrayToObject($array)
    {
        // First we convert the array to a json string
        $json = json_encode($array);

        // The we convert the json string to a stdClass()
        $object = json_decode($json);

        return $object;
    }


    /**
     * Convert a object to an array
     *
     * @param   object  $object The object we want to convert
     *
     * @return  array
     */
    public static function objectToArray($object)
    {
        // First we convert the object into a json string
        $json = json_encode($object);

        // Then we convert the json string to an array
        $array = json_decode($json, true);

        return $array;
    }

    public static function removeNullFieldsFromObject($object){
        $object = (object) array_filter((array) $object, function ($val) {
            return !is_null($val);
        });

        return $object;
    }
}