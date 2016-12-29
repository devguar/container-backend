<?php
/**
 * Created by PhpStorm.
 * User: lucas.guarnieri
 * Date: 03/11/2016
 * Time: 13:27
 */

namespace Devguar\OContainer\Repositories\Criteria\Miscellaneous;

use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Contracts\CriteriaInterface;

use Illuminate\Support\Facades\Auth;

class Ativo implements CriteriaInterface {

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $table = $model->getModel()->getTable();
        $model = $model->where($table.'.ativo', '=', 'S');
        return $model;
    }
}