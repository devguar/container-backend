<?php

namespace Devguar\OContainer\Models;

use Devguar\OContainer\Util\BiscoiteiroHelper;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

trait RememberableModel
{
    public static function bootRememberableModel()
    {
//        if (!App::environment('testing')){
            static::created(function ($model) {
                static::forget($model->id);
            });
            static::updated(function ($model) {
                static::forget($model->id);
            });
            static::deleted(function ($model) {
                static::forget($model->id);
            });
//        }
    }

    public static function forget($id)
    {
        BiscoiteiroHelper::destroyById(self::class, $id);
    }

    public static function findOrRemember($id)
    {
//        \Debugbar::info("Antes");

        $object = Cache::remember(self::class.'_'.$id, 10, function () use($id) {
//            \Debugbar::info("Nao tinha cache, gravou");
            return self::find($id);
        });

//        \Debugbar::info("Depois");

//        dd($object);

        return $object;
    }
}