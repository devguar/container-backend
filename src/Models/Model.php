<?php
/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 22/03/2016
 * Time: 23:55
 */

namespace Devguar\OContainer\Models;

use Illuminate\Database\Eloquent\Model as OriginalModel;
use Illuminate\Support\Facades\Auth;

abstract class Model extends OriginalModel
{
    protected $hasCompanyId = false;

    /**
     * @return boolean
     */
    public function hasCompanyId()
    {
        return $this->hasCompanyId;
    }

    public function save(array $options = [])
    {
        if ($this->hasCompanyId){
            $user = Auth::user();
            //print_r($user);
            //print_r($options);

            $this->empresa_id = $user->empresa_id;
        }
        // before save code
        parent::save();
        // after save code
    }

    public static function formatInline($id = null){
        $object = self::find($id);
        return $object->nome;
    }
}