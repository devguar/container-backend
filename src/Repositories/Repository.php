<?php
/**
 * Created by PhpStorm.
 * User: lucas.guarnieri
 * Date: 04/11/2016
 * Time: 13:08
 */

namespace Devguar\OContainer\Repositories;

use Devguar\OContainer\Models\Model;
use Devguar\OContainer\Scopes\Miscellaneous\EmpresaLogada;
use Devguar\OContainer\Scopes\BootstrapTable;
use Illuminate\Database\Eloquent\Scope;

abstract class Repository
{
    protected $searchableFields = [];
    protected $joins = [];

    private $scopes = [];

    public function __construct()
    {
        $this->boot();
    }

    abstract public function model();

    public function joins(){
        return $this->joins;
    }

    public function searchableFields(){
        return $this->searchableFields;
    }

    public function rules($values, $id = null){
        return [];
    }

    public function boot(){

    }

    private function reboot(){
        $this->scopes = [];
    }

    public function addQueryScope(Scope $scope){
        $this->scopes[get_class($scope)] = $scope;
    }

    public function removeQueryScope(Scope $scope){
        unset($this->scopes[get_class($scope)]);
    }

    public function bootstrapTable(string $search = null, string $sort = null, string $order = null, int $limit = null, int $offset = null, int &$count){
        $this->addQueryScope(new BootstrapTable\Joins($this));
        $this->addQueryScope(new BootstrapTable\Search($this, $search));
        $builder = $this->makeBuilderWithScopes();

        $count = $builder->get()->count();

        $this->addQueryScope(new BootstrapTable\Select($this));
        $this->addQueryScope(new BootstrapTable\Order($this, $sort, $order));
        $this->addQueryScope(new BootstrapTable\Pagination($this, $limit, $offset));
        $builder = $this->makeBuilderWithScopes();

        $result = $builder->get();

        $this->reboot();

        return $result;
    }

    public function getModel() : Model{
        $className = $this->model();
        $model = new $className;

        if ($model->hasCompanyId()){
            $model->addGlobalScope(new EmpresaLogada());
        }

        return $model;
    }

    private function makeBuilderWithScopes(){
        $builder = $this->getModel()->newQuery();

        foreach ($this->scopes as $name => $scope) {
            $builder->withGlobalScope($name, $scope);
        }

        return $builder;
    }

    public function find($id){
        return $this->makeBuilderWithScopes()->findOrFail($id);
    }

    public function all(){
        return $this->makeBuilderWithScopes()->get();
    }
}