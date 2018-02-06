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

class Select implements Scope
{
    Use TreatField;

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
            $field = $this->treatField($table, $field, $condition);

            if ($field->function){
                $builder->addSelect(\DB::raw('('.$field->function.') as '.$field->alias));
            }else{
                $builder->addSelect( $field->table.'.'.$field->field.' as '.$field->alias);
            }
        }
    }
}