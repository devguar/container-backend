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
    static public function loggedUser(){
        if (\Auth::user()){
            return \Auth::user();
        }

        $empresa = \App\Models\Configuracoes\Empresa::orderBy('id','desc')->first();
        $user = $empresa->usuarios->first();
        return $user;
    }

    static public function isRunningTests(){
        
    }
}