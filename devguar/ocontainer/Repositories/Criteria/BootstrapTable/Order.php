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

class Order implements CriteriaInterface {
    private $campo;
    private $ordem;

    /**
     * Paginacao constructor.
     */
    public function __construct($campo, $ordem)
    {
        $this->campo = $campo;
        $this->ordem = $ordem;
    }

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $table = $model->getModel()->getTable();

        if ($this->campo){
            $field = $this->campo;

            if (strpos($field,'.') === false){
                $field = $table.'.'.$field;
            }

            if ($this->ordem){
                $model = $model->orderBy($field,$this->ordem);
            }else{
                $model = $model->orderBy($field,'asc');
            }
        }else{
            $fieldsSearchable = $repository->getFieldsSearchable();

            foreach ($fieldsSearchable as $field => $condition) {
                if (strpos($field,'.') === false){
                    $order = $table.'.'.$field;
                }else{
                    $order = $field;
                }

                $model = $model->orderBy($order, 'asc');
            }
        }

        return $model;
    }
}