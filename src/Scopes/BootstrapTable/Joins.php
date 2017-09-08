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

class Joins implements Scope {
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function apply(Builder $builder, Model $model)
    {
        $joins = $this->repository->joins();
        $table_from = $model->getTable();

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

            $builder->leftJoin($table, $field, '=', $join.'.'.$foreign_field);
        }
    }
}