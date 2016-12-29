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

class Search implements CriteriaInterface {
    private $search;

    /**
     * Paginacao constructor.
     */
    public function __construct($search)
    {
        $this->search = $search;
    }

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        if ($this->search){
            $table = $model->getModel()->getTable();
            $fieldsSearchable = $repository->getFieldsSearchable();

            $model->where(function($queryContainer) use ($fieldsSearchable, $table){
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

        return $model;
    }
}