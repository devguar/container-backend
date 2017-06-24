<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 11/06/17
 * Time: 14:47
 */

namespace Devguar\OContainer\Permissions;


abstract class AbstractRule
{
    private $errorMessage = "Sem permissão para acessar esta funcionalidade.";
    public $model;

    abstract public function test();

    public function user(){
        return \Auth::user();
    }

    public function getErrorMessage(){
        return $this->errorMessage;
    }

    public function setErrorMessage($error){
        $this->errorMessage = $error;
    }
}