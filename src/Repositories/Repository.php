<?php
/**
 * Created by PhpStorm.
 * User: lucas.guarnieri
 * Date: 04/11/2016
 * Time: 13:08
 */

namespace Devguar\OContainer\Repositories;

use Devguar\OContainer\Scopes\Miscellaneous\EmpresaLogada;
use Devguar\OContainer\Scopes\BootstrapTable;
use Devguar\OContainer\Scopes\Miscellaneous\SetarEmpresa;
use Illuminate\Database\Eloquent\Scope;

abstract class Repository
{
    protected $searchableFields = [];
    protected $joins = [];

    private $scopes = [];
    private $ignoredScopes = [];

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

    public function ignoreQueryScope(Scope $scope){
        $this->ignoredScopes[get_class($scope)] = $scope;
    }

    public function removeQueryScope(Scope $scope){
        if (isset($this->scopes[get_class($scope)])){
            unset($this->scopes[get_class($scope)]);
        }

        if (isset($this->ignoredScopes[get_class($scope)])) {
            unset($this->ignoredScopes[get_class($scope)]);
        }
    }

    public function bootstrapTable(string $search = null, string $sort = null, string $order = null, int $limit = null, int $offset = null, int &$count){
        $this->addQueryScope(new BootstrapTable\Joins($this));
        $this->addQueryScope(new BootstrapTable\Search($this, $search));
        $builder = $this->makeBuilderWithScopes();

//        \DB::enableQueryLog();

        $count = $builder->get()->count();

//        dd (\DB::getQueryLog());

        $this->addQueryScope(new BootstrapTable\Select($this));
        $this->addQueryScope(new BootstrapTable\Order($this, $sort, $order));
        $this->addQueryScope(new BootstrapTable\Pagination($this, $limit, $offset));
        $builder = $this->makeBuilderWithScopes();

        $result = $builder->get();

        $this->reboot();

        return $result;
    }

    public function getModel() : \Illuminate\Database\Eloquent\Model{
        $className = $this->model();
        $model = new $className;

        if ($model::getGlobalScope(new SetarEmpresa())){
            $this->addQueryScope(new EmpresaLogada());
        }

        return $model;
    }

    private function makeBuilderWithScopes(){
        $builder = $this->getModel()->withoutGlobalScopes($this->ignoredScopes)->newQuery();

        foreach ($this->scopes as $name => $scope) {
            if (!in_array($scope, $this->ignoredScopes)){
                $builder->withGlobalScope($name, $scope);
            }
        }

        return $builder;
    }

    public function getNewQuery(){
        return $this->makeBuilderWithScopes();
    }

    public function find($id){
//        \DB::enableQueryLog();
        $return = $this->getNewQuery()->find($id);
//        dd (\DB::getQueryLog());
        return $return;
    }

    public function findOrFail($id){
        $return = $this->getNewQuery()->findOrFail($id);
        return $return;
    }

    public function all(){
        return $this->getNewQuery()->get();
    }
}