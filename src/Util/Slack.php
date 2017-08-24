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

            if ($anexo){
                $client->attach(['text' => $anexo])->send($message);;
            }else{
                $client->send($message);
            }
        }
    }
}