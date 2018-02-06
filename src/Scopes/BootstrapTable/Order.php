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

class Order implements Scope
{
    Use TreatField;

    private $repository;
    private $campo;
    private $ordem;

    public function __construct(Repository $repository, $campo, $ordem)
    {
        $this->repository = $repository;
        $this->campo = $campo;
        $this->ordem = ($ordem ? $ordem : 'asc');
    }

    public function apply(Builder $builder, Model $model)
    {
        $table = $model->getTable();

        if ($this->campo){
            $builder->orderBy($this->campo,$this->ordem);
        }else{
            $fieldsSearchable = $this->repository->searchableFields();

            foreach ($fieldsSearchable as $field => $condition) {
                $field = $this->treatField($table, $field, $condition);
                $builder->orderBy($field->alias, 'asc');
            }
        }
    }
}