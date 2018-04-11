<?php
/**
 * Created by PhpStorm.
 * User: lucas.guarnieri
 * Date: 03/11/2016
 * Time: 13:27
 */

namespace Devguar\OContainer\Scopes\Miscellaneous;

use Devguar\OContainer\Exceptions\InvalidCompanyException;
use Devguar\OContainer\Tests\TestHelper;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Support\Facades\Auth;

class EmpresaLogada implements Scope {
    public function apply(Builder $builder, Model $model)
    {
        if (method_exists($model,'getDefaultEmpresaId')){
            $empresa_id = $model->getDefaultEmpresaId();

            if ($empresa_id){
                $table = $model->getTable();
                $builder->where($table.'.empresa_id', '=', $empresa_id);
            }else{
                throw new InvalidCompanyException();
            }
        }else{
           throw new InvalidCompanyException();
        }
    }
}