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

class Search implements Scope
{
    Use TreatField;

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
                    $field = $this->treatField($table, $field, $condition);

                    if ($field->operator != Repository::Repository_Operator_Ignore){
                        if ($field->function){
                            $fieldFull = \DB::raw($field->function);
                        }else{
                            $fieldFull = $field->table.'.'.$field->field;
                        }

                        if ($primeiroFiltro){
                            $primeiroFiltro = false;

                            if ($condition == Repository::Repository_Operator_Like){
                                $queryContainer->where($fieldFull,$field->operator, '%'.$this->search.'%');
                            }else{
                                $queryContainer->where($fieldFull,$field->operator,$this->search);
                            }
                        }else{
                            if ($condition == Repository::Repository_Operator_Like){
                                $queryContainer->orWhere($fieldFull,$field->operator, '%'.$this->search.'%');
                            }else{
                                $queryContainer->orWhere($fieldFull,$field->operator,$this->search);
                            }
                        }
                    }
                }
            });
        }
    }
}