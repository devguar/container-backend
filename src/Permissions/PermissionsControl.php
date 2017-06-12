<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 11/06/17
 * Time: 14:50
 */

namespace Devguar\OContainer\Permissions;

class PermissionsControl
{
    private static $errorMessage;

    public static function hasPermission($ruleClass, $model = null)
    {
        return self::testRule($ruleClass, $model);
    }

    private static function testRule($className, $model){
        if (is_array($className)){
            foreach ($className as $oneRule){
                $valid = self::testRule($oneRule, $model);

                if (!$valid){
                    return false;
                }
            }

            return true;
        }else{
            $rule = new $className;
            $rule->model = $model;

            //echo 'regra: '.$className.'<br/>';

            self::$errorMessage = $rule->getErrorMessage();
            return $rule->test();
        }
    }

    public static function hasPermissionOrAbort($ruleClass, $model = null)
    {
        if (!self::hasPermission($ruleClass,$model)){
            abort('403',self::$errorMessage);
        }
    }
}