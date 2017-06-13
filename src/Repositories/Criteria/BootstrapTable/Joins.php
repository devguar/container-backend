<?php
/**
 * Created by PhpStorm.
 * User: lucas.guarnieri
 * Date: 03/11/2016
 * Time: 13:27
 */

namespace Devguar\OContainer\Repositories\Criteria\BootstrapTable;

use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Contracts\CriteriaInterface;

class Joins implements CriteriaInterface {
    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $joins = $repository->getJoins();
        $table_from = $model->getModel()->getTable();

        foreach($joins as $join => $details) {
            $foreign_field = 'id';

            if (is_array($details)){
                $field = $details['field'];
                $table = $details['table'];
                $foreign_field = $details['field_foreign'];
                $table = $table.' as '.$join;
            }else{
                $field = $details;
                $table = $join;
            }

            if (strpos($field,'.') === false){
                $field = $table_from.'.'.$field;
            }

            $model->leftJoin($table, $field, '=', $join.'.'.$foreign_field);
        }

        return $model;
    }
}