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

class Order implements Scope {
    private $repository;
    private $campo;
    private $ordem;

    public function __construct(Repository $repository, $campo, $ordem)
    {
        $this->repository = $repository;
        $this->campo = $campo;
        $this->ordem = $ordem;
    }

    public function apply(Builder $builder, Model $model)
    {
        $table = $model->getTable();

        if ($this->campo){
            $field = $this->campo;

            if ($this->ordem){
                $builder->orderBy($field,$this->ordem);
            }else{
                $builder->orderBy($field,'asc');
            }
        }else{
            $fieldsSearchable = $this->repository->searchableFields();

            foreach ($fieldsSearchable as $field => $condition) {
                if ((strpos($field,'.') === false) && ($condition != Repository::Repository_Operator_Function)){
                    $order = $table.'.'.$field;
                }else{
                    $order = $field;
                }

                $builder->orderBy($order, 'asc');
            }
        }
    }
}