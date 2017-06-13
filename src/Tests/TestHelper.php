<?php
/**
 * Created by PhpStorm.
 * User: lucas.guarnieri
 * Date: 17/11/2016
 * Time: 10:29
 */

namespace Devguar\OContainer\Tests;

class TestHelper
{
    static public function lastRecord($model){
        $user = self::loggedUser();

        if ($model->hasCompanyId()){
            $idioma = $model::where('empresa_id',$user->empresa_id)->orderBy('id','desc')->first();
        }else{
            $idioma = $model::orderBy('id','desc')->first();
        }

        return $idioma;
    }

    static public function firstRecord($model){
        $user = self::loggedUser();

        if ($model->hasCompanyId()){
            $idioma = $model::where('empresa_id',$user->empresa_id)->orderBy('id','asc')->first();
        }else{
            $idioma = $model::orderBy('id','asc')->first();
        }

        return $idioma;
    }

    static public function loggedUser(){
        $user = \App\Models\Usuario\Usuario::first();
        return $user;
    }

    static public function isRunningTests(){
        return (defined('PHPUNIT_RUNNINGTESTS') == 1);
    }
}