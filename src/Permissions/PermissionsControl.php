<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 11/06/17
 * Time: 14:50
 */

namespace Devguar\OContainer\Permissions;

use Devguar\OContainer\Util\BiscoiteiroHelper;

class PermissionsControl
{
    private static $errorMessage;

    public static function hasPermission($ruleClass, $model = null)
    {
        return self::testRule($ruleClass, $model);
    }

    public static function asMiddleware($rules){
        $rules = self::getAllRulesRecursive($rules);
        $rules = implode('|',$rules);

        //echo ($rules.'<br/><br/><br/>');

        return 'permissionmiddleware:'.$rules;
    }

    private static function getAllRulesRecursive($className){
        $rules = array();

        if (is_array($className)){
            foreach ($className as $oneRule){
                $rulesInside = self::getAllRulesRecursive($oneRule);
                $rules = array_merge($rules, $rulesInside);
            }

            return $rules;
        }else{
            $rules[] = $className;
        }

        return $rules;
    }

    private static function testRule($className, $model){
        $rules = self::getAllRulesRecursive($className);

        foreach ($rules as $className){
            $rule = new $className;
            $rule->model = $model;

            $accept = cache()->remember($className.'_permission_user_'.\Auth::user()->id, 10, function() use($rule){
                return $rule->test();
            });

            if (!$accept){
                self::$errorMessage = $rule->getErrorMessage();
                return false;
            }
        }

        return true;
    }

    public static function hasPermissionOrAbort($ruleClass, $model = null)
    {
        if (!self::hasPermission($ruleClass,$model)){
            abort('403',self::$errorMessage);
        }
    }
}