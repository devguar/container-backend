<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 16/11/17
 * Time: 00:33
 */

namespace Devguar\OContainer\Models;

use Ramsey\Uuid\Uuid;

trait Uuids
{
    /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Uuid::uuid4()->toString();
        });
    }
}