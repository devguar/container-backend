<?php
/**
 * Created by PhpStorm.
 * User: lucas.guarnieri
 * Date: 03/11/2016
 * Time: 13:27
 */

namespace Devguar\OContainer\Scopes\Miscellaneous;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FiltroFalso implements Scope {
    public function apply(Builder $builder, Model $model)
    {
        $table = $builder->getModel()->getTable();
        $model = $builder->where($table.'.id', '<>', null);
        return $model;
    }
}