<?php
/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 22/03/2016
 * Time: 23:55
 */

namespace Devguar\OContainer\Models;

use Devguar\OContainer\Exceptions\InvalidCompanyException;
use Devguar\OContainer\Exceptions\InvalidConfigurationException;
use Devguar\OContainer\Scopes\Miscellaneous\EmpresaLogada;
use Devguar\OContainer\Scopes\Miscellaneous\SetarEmpresa;
use Devguar\OContainer\Tests\TestHelper;
use Illuminate\Database\Eloquent\Model as OriginalModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

abstract class Model extends OriginalModel
{
    private $defaultEmpresaId = null;

    public function __construct(array $attributes = [])
    {
        $this->setDefaultEmpresa();
        parent::__construct($attributes);
    }

    public function setDefaultEmpresa(){
        if (App::environment('testing')){
            $user = TestHelper::loggedUser();
        }else {
            $user = Auth::user();
        }

        if ($user) {
            $this->setDefaultEmpresaId($user->empresa_id);
        }
    }

    public function getDefaultEmpresaId(){
        return $this->defaultEmpresaId;
    }

    public function setDefaultEmpresaId($empresaId){
        $this->defaultEmpresaId = $empresaId;
    }

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
            if ($this->defaultEmpresaId){
                $this->empresa_id = $this->defaultEmpresaId;
            }else{
                if (!$this->empresa_id){
                    throw new InvalidCompanyException();
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