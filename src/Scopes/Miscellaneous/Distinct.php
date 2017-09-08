<?php
/**
 * Created by PhpStorm.
 * User: lucas.guarnieri
 * Date: 03/11/2016
 * Time: 13:27
 */

namespace Devguar\OContainer\Scopes\Miscellaneous;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Distinct implements Scope {
    private $fields;

    public function __construct($fields)
    {
        $this->fields = $fields;
    }

    public function apply(Builder $builder, Model $model)
    {
        $model = $builder->select($this->fields);
        $model = $builder->distinct();
        return $model;
    }
}