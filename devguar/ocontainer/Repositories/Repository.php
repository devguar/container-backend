<?php
/**
 * Created by PhpStorm.
 * User: lucas.guarnieri
 * Date: 04/11/2016
 * Time: 13:08
 */

namespace Devguar\OContainer\Repositories;

use Devguar\OContainer\Model;
use Prettus\Repository\Eloquent\BaseRepository as OriginalBaseRepository;
use Devguar\OContainer\Repositories\Criteria\Miscellaneous\EmpresaLogada;

abstract class Repository extends OriginalBaseRepository
{
    protected $joins = [];

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param Model $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return array
     */
    public function getJoins()
    {
        return $this->joins;
    }

    /**
     * @param array $joins
     */
    public function setJoins($joins)
    {
        $this->joins = $joins;
    }

    public function rules($values){
        return [];
    }

    public function boot()
    {
        if ($this->getModel()->hasCompanyId()){
            $this->pushCriteria(new EmpresaLogada());
        }

        parent::boot(); // TODO: Change the autogenerated stub
    }

}