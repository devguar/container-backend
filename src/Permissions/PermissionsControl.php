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
        if (is_array($ruleClass)){
            foreach ($ruleClass as $oneRule){
                $rule = new $oneRule;
                $rule->model = $model;

                if (!$rule->test()){
                    self::$errorMessage = $rule->getErrorMessage();
                    return false;
                }
            }

            return true;
        }else{
            $rule = new $ruleClass;
            $rule->model = $model;

            self::$errorMessage = $rule->getErrorMessage();
            return $rule->test();
        }
    }

    public static function hasPermissionOrAbort($ruleClass, $model = null)
    {
        if (!self::hasPermission($ruleClass)){
            abort('403',self::$errorMessage);
        }
    }
}