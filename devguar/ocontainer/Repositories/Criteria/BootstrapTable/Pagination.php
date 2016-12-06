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

class Pagination implements CriteriaInterface {
    private $limit;
    private $offset;

    /**
     * Paginacao constructor.
     */
    public function __construct($limit, $offset)
    {
        $this->limit = $limit;
        $this->offset = $offset;
    }

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        if ($this->limit){
            $model = $model->limit($this->limit);

            if ($this->offset){
                $model = $model->offset($this->offset);
            }
        }

        return $model;
    }
}