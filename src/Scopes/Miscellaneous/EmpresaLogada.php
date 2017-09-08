<?php
/**
 * Created by PhpStorm.
 * User: lucas.guarnieri
 * Date: 03/11/2016
 * Time: 13:27
 */

namespace Devguar\OContainer\Scopes\Miscellaneous;

use Devguar\OContainer\Tests\TestHelper;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Support\Facades\Auth;

class EmpresaLogada implements Scope {
    public function apply(Builder $builder, Model $model)
    {
        if (App::environment('testing')){
            $user = TestHelper::loggedUser();
        }else{
            $user = Auth::user();
        }

        $table = $model->getTable();
        $builder->where($table.'.empresa_id', '=', $user->empresa_id);
    }
}