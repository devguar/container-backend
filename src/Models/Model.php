<?php
/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 22/03/2016
 * Time: 23:55
 */

namespace Devguar\OContainer\Models;

use Devguar\OContainer\Exceptions\InvalidConfigurationException;
use Devguar\OContainer\Scopes\Miscellaneous\EmpresaLogada;
use Devguar\OContainer\Scopes\Miscellaneous\SetarEmpresa;
use Illuminate\Database\Eloquent\Model as OriginalModel;
use Illuminate\Support\Facades\Auth;

abstract class Model extends OriginalModel
{
    use UuidForKey;

    protected $fieldSearchable = [];
    protected $joins = [];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function getJoins()
    {
        return $this->joins;
    }

    public function save(array $options = [])
    {
        if (static::getGlobalScope(new SetarEmpresa())){
            $user = Auth::user();

            if ($user){
                $this->empresa_id = $user->empresa_id;
            }else{
                if (!$this->empresa_id){
                    throw new InvalidConfigurationException("Impossível inserir registro.");
                }
            }
        }

        parent::save();
    }

    public static function formatInline($id){
        $object = self::find($id);
        return $object->nome;
    }
}