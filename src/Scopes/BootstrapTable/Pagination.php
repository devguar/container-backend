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

class Pagination implements Scope {
    private $repository;
    private $limit;
    private $offset;

    public function __construct(Repository $repository, $limit, $offset)
    {
        $this->repository = $repository;
        $this->limit = $limit;
        $this->offset = $offset;
    }

    public function apply(Builder $builder, Model $model)
    {
        if ($this->limit){
            $builder->limit($this->limit);

            if ($this->offset){
                $builder->offset($this->offset);
            }
        }
    }
}