<?php
/**
 * Created by PhpStorm.
 * User: lucas.guarnieri
 * Date: 06/02/2018
 * Time: 13:57
 */

namespace Devguar\OContainer\Scopes\BootstrapTable;


use Devguar\OContainer\Repositories\Repository;

trait TreatField
{
    public function treatField($table, $field, $operator) : FieldObject{
        $fieldObject = new FieldObject();

        $field = explode('.',$field);

        if ($operator == Repository::Repository_Operator_Function){
            $fieldObject->function = $this->repository->getFieldFunction($field, $operator);
            $fieldObject->alias = str_replace('.','_',$field);
            $fieldObject->operator = Repository::Repository_Operator_Like;
        }else{
            if (count($field) == 1){
                //Campo da tabela pai
                $fieldObject->table = $table;
                $fieldObject->field = $field[0];

                if ($fieldObject->field == "ativo"){
                    $fieldObject->alias = $fieldObject->field;
                }else{
                    $fieldObject->alias = $fieldObject->table.'_'.$fieldObject->field;
                }
            }else{
                //Campo de outra tabela
                $fieldObject->table = $field[0];
                $fieldObject->field = $field[1];
                $fieldObject->alias = $fieldObject->table.'_'.$fieldObject->field;
            }

            $fieldObject->operator = $operator;
            if (!$operator){
                $fieldObject->operator = Repository::Repository_Operator_Like;
            }
        }

        return $fieldObject;
    }
}