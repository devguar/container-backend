<?php
/**
 * Created by PhpStorm.
 * User: lucas.guarnieri
 * Date: 03/11/2016
 * Time: 13:27
 */

namespace Devguar\OContainer\Repositories\Criteria\Miscellaneous;

use Devguar\OContainer\Tests\TestHelper;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Contracts\CriteriaInterface;

use Illuminate\Support\Facades\Auth;

class EmpresaLogada implements CriteriaInterface {

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        if (TestHelper::isRunningTests()){
            $user = TestHelper::loggedUser();
        }else{
            $user = Auth::user();
        }

        $table = $model->getModel()->getTable();
        $model = $model->where($table.'.empresa_id', '=', $user->empresa_id);
        return $model;
    }
}