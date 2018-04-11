<?php
/**
 * Created by PhpStorm.
 * User: lucas.guarnieri
 * Date: 29/08/2017
 * Time: 09:44
 */

namespace Devguar\OContainer\Exceptions;

use Throwable;

class InvalidCompanyException extends \Exception
{
    public function __construct($message = "Impossível definir contexto da empresa.", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}