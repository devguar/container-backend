<?php

namespace Devguar\OContainer\Models;

use Devguar\OContainer\Util\BiscoiteiroHelper;
use Illuminate\Support\Facades\App;

trait RememberableModel
{
    public static function bootRememberableModel()
    {
        if (!App::environment('testing')){
            static::created(function ($model) {
                static::forget($model->id);
            });
            static::updated(function ($model) {
                static::forget($model->id);
            });
            static::deleted(function ($model) {
                static::forget($model->id);
            });
            static::retrieved(function ($model) {
                static::memorize($model);
            });
        }
    }

    public static function forget($id)
    {
        BiscoiteiroHelper::destroyById(self::class, $id);
    }

    public static function memorize($model)
    {
        BiscoiteiroHelper::setById(self::class, $model->id, $model);
    }

    public static function findOrRemember($id)
    {
        $cache = BiscoiteiroHelper::getById(self::class, $id);

        if ($cache) {
            return $cache;
        }

        return self::find($id);
    }
}