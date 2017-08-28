<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 07/08/17
 * Time: 20:08
 */

namespace Devguar\OContainer\Util;

use Maknz\Slack\Client as SlackClient;

class Slack
{
    public static function send($hookUrl, $message, $anexo = null){
        if ($hookUrl){
            $client = new SlackClient($hookUrl);

            $attachment = array();

            if ($anexo) {
                $attachment = array('text' => $anexo);
            }

            $user = \Auth::user();
            if ($user){
                $attachment['author_name'] = $user->nome.' - '.$user->empresa->razao_social;
            }

            //dd($hookUrl);
            //dd($attachment);

            if (count($attachment) > 0)
            {
                $client->attach($attachment)->send($message);;
            }
            else
            {
                $client->send($message);
            }
        }
    }
}