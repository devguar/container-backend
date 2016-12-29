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

class Select implements CriteriaInterface {
    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $table = $model->getModel()->getTable();
        $model->select($table.'.id as id');

        $fieldsSearchable = $repository->getFieldsSearchable();

        foreach ($fieldsSearchable as $field => $condition){
            if ($field == "ativo")
                $model->addSelect($field.' as ativo');
            else{
                if (strpos($field,'.') === false){
                    $field = $table.'.'.$field;
                }

                $model->addSelect($field.' as '.str_replace('.','_',$field));
            }
        }

        return $model;
    }
}