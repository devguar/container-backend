<?php
/**
 * Created by PhpStorm.
 * User: lucas.guarnieri
 * Date: 03/11/2016
 * Time: 13:27
 */

namespace Devguar\OContainer\Scopes\BootstrapTable;

use Devguar\OContainer\Repositories\Repository;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Select implements Scope {

    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function apply(Builder $builder, Model $model)
    {
        $table = $model->getTable();
        $builder->select($table.'.id as id');

        $fieldsSearchable = $this->repository->searchableFields();

        foreach ($fieldsSearchable as $field => $condition){
            if ($field == "ativo")
                $builder->addSelect($field.' as ativo');
            else{
                if (strpos($field,'.') === false){
                    $field = $table.'.'.$field;
                }

                $builder->addSelect($field.' as '.str_replace('.','_',$field));
            }
        }
    }
}