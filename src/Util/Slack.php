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
    public static function send($hookUrl, $message){
        if ($hookUrl){
            $client = new SlackClient($hookUrl);
            $client->send($message);
        }
    }
}