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

class Search implements Scope {
    private $repository;
    private $search;

    public function __construct(Repository $repository, string $search = null)
    {
        $this->repository = $repository;
        $this->search = $search;
    }

    public function apply(Builder $builder, Model $model)
    {
        if ($this->search){
            $table = $model->getTable();
            $fieldsSearchable = $this->repository->searchableFields();

            $builder->where(function($queryContainer) use ($fieldsSearchable, $table){
                $primeiroFiltro = true;

                foreach ($fieldsSearchable as $field => $condition){
                    if ($condition == "")
                        $condition = "like";

                    if (strpos($field,'.') === false){
                        $field = $table.'.'.$field;
                    }

                    if ($primeiroFiltro){
                        $primeiroFiltro = false;

                        if ($condition == "like"){
                            $queryContainer->where($field,$condition, '%'.$this->search.'%');
                        }else{
                            $queryContainer->where($field,$condition,$this->search);
                        }
                    }else{
                        if ($condition == "like"){
                            $queryContainer->orWhere($field,$condition, '%'.$this->search.'%');
                        }else{
                            $queryContainer->orWhere($field,$condition,$this->search);
                        }
                    }
                }
            });
        }
    }
}