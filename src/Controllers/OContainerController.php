<?php

namespace Devguar\OContainer\Controllers;

use App\Http\Controllers\Controller as OriginalController;
use Illuminate\Support\Facades\Session;

abstract class OContainerController extends OriginalController
{
    public function messageSuccess($message){
        Session::flash('message-success', $message);
    }

    public function messageInfo($message){
        Session::flash('message-info', $message);
    }

    public function messageDanger($message){
        Session::flash('message-danger', $message);
    }
}