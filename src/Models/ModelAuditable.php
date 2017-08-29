<?php
/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 22/03/2016
 * Time: 23:55
 */

namespace Devguar\OContainer\Models;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

abstract class ModelAuditable extends Model implements AuditableContract
{
    use Auditable;
}