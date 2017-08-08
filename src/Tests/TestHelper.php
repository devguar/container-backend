<?php
/**
 * Created by PhpStorm.
 * User: lucas.guarnieri
 * Date: 17/11/2016
 * Time: 10:29
 */

namespace Devguar\OContainer\Tests;

use Illuminate\Support\Facades\App;

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
        $empresa = \App\Models\Configuracoes\Empresa::orderBy('id','desc')->first();
        $user = $empresa->usuarios->first();
        return $user;
    }

    static public function isRunningTests(){
        
    }
}