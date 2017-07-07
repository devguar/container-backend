<?php

namespace Devguar\OContainer\Controllers;

use App\Http\Controllers\Controller as OriginalController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

abstract class OContainerController extends OriginalController
{
    public function messageSuccessURL($message, $url){
        $this->messageSuccess('<a href="'.$url.'" class="mask-tooltip" title="Visualizar">'.$message.' <span class="glyphicon glyphicon-zoom-in"></span></a>');
    }

    public function messageSuccess($message){
        Session::flash('message-success', $message);
    }

    public function messageInfo($message){
        Session::flash('message-info', $message);
    }

    public function messageDanger($message){
        Session::flash('message-danger', $message);
    }

    public function user(){
        return Auth::user();
    }
}