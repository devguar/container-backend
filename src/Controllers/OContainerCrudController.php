<?php

namespace Devguar\OContainer\Controllers;

use Devguar\OContainer\Exceptions\InvalidArgumentException;
use Devguar\OContainer\Repositories\Repository;
use Devguar\OContainer\Scopes\Miscellaneous\FiltroFalso;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Devguar\OContainer\Scopes\BootstrapTable;
use Exception;
use Illuminate\Support\Facades\DB;

abstract class OContainerCrudController extends OContainerController
{
    private $repository = null;
    private $viewsfolder = null;

    /**
     * @return null
     */
    public function getViewsfolder()
    {
        return $this->viewsfolder;
    }

    /**
     * @param null $viewsfolder
     */
    public function setViewsfolder($viewsfolder)
    {
        $this->viewsfolder = $viewsfolder;
    }

    /**
     * @return null
     */
    public function getRepository() : Repository
    {
        return $this->repository;
    }

    /**
     * @param null $repository
     */
    public function setRepository($repository)
    {
        $this->repository = $repository;
    }

    public function __construct($repository, $viewsfolder)
    {
        $this->repository = $repository;
        $this->viewsfolder = $viewsfolder;
    }

    public function index()
    {
        return $this->loadViewIndex()->withController($this);
    }

    public function loadViewIndex(){
        return view($this->viewsfolder.'.index')->withController($this);
    }

    public function getListContentByRepository($repository)
    {
        $search = isset($_GET['search']) ? $_GET['search'] : null;
        $sort = isset($_GET['sort']) ? $_GET['sort'] : null;
        $order = isset($_GET['order']) ? $_GET['order'] : null;
        $limit = isset($_GET['limit']) ? $_GET['limit'] : null;
        $offset = isset($_GET['offset']) ? $_GET['offset'] : null;

        $return = new \stdClass();
        $return->total = 0;
        $return->rows = $repository->bootstrapTable($search, $sort, $order, $limit, $offset, $return->total);
        $return->success = true;

        return $return;
    }

    public function mountListContentByRepository($repository){
        $return = $this->getListContentByRepository($repository);
        return $this->formatlistcontent($return);
    }

    public function listcontent(){
        try{
            return $this->mountListContentByRepository($this->repository);
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function listcontentByRepository($repository){
        try{
            return $this->mountListContentByRepository($repository);
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function formatlistcontent($content){
        $rows = array();

        foreach($content->rows as $row){
            $actions = $this->listActions($row);

            if (count($actions) > 0){
                $row->actions = implode(" ", $actions);
            }else{
                $row->actions = null;
            }

            $rows[] = $row;
        }

        $content->rows = $rows;

        return response()->json($content);
    }

    public function listActions($row){
        return null;
    }

    public function create()
    {
        try{
            return $this->loadViewCreate();
        }catch(Exception $e){
            return view('errors.custom')->withException($e);
        }
    }

    public function loadViewCreate(){
        $object = $this->repository->getModel();
        return view($this->viewsfolder.'.create-edit')->withObject($object)->withController($this);
    }

    public function edit($id)
    {
        try{
            $object = $this->repository->find($id);

            if (!isset($object->id))
                throw new InvalidArgumentException("Registro não encontrada para edição.");

            return $this->loadViewEdit($object);
        }catch(Exception $e){
            \Log::error(\Route::getCurrentRoute()->getActionName(), ['message' => $e->getMessage(), 'trace' => $e->getTrace()]);
            return view('errors.custom')->withException($e);
        }
    }

    public function loadViewEdit($object){
        return view($this->viewsfolder.'.create-edit')->withObject($object)->withController($this);
    }

    abstract protected function doStore(Request $request, $id = null);

    public function store(Request $request, $id = null)
    {
        $this->validate($request, $this->repository->rules($request->all(), $id));
        return $this->doStore($request,$id);
    }

    abstract protected function doDelete($id);

    public function delete($id)
    {
        try {
            $object = $this->repository->find($id);

            if (!isset($object->id))
                throw new InvalidArgumentException("Registro não encontrada para exclusão.");

            return $this->doDelete($id);
        }catch (QueryException $e){
            if ($e->getCode() == 23000){
                $e->descricao = $this->customMessageForeignKeyException($e);
                return view('errors.custom')->withException($e);
            }else{
                return view('errors.custom')->withException($e);
            }
        }catch(Exception $e){
            return view('errors.custom')->withException($e);
        }
    }

    protected function customMessageForeignKeyException(QueryException $e){
        return 'Não é possível excluir um registro que está sendo utilizado em outros lugares do sistema.';
    }

    public function autocomplete(){
        try{
            $termo = (isset($_GET["termo"]) ? $_GET["termo"] : null);

            $this->repository->addQueryScope(new FiltroFalso());
            $this->repository->addQueryScope(new BootstrapTable\Select($this->getRepository()));
            $this->repository->addQueryScope(new BootstrapTable\Joins($this->getRepository()));
            $this->repository->addQueryScope(new BootstrapTable\Search($this->getRepository(), $termo));

            $rows = $this->repository->all();

            return $this->formatautocomplete($rows);
        }catch(Exception $e){
            \Log::error(\Route::getCurrentRoute()->getActionName(), ['message' => $e->getMessage(), 'trace' => $e->getTrace()]);
            return response()->json(array('error' => 'Erro ao carregar regitros da lista. '.$e->getMessage()));
        }
    }

    function formatTextValueAutoComplete($row){
        $table = $this->repository->getModel()->getTable();
        $field = $table.'_nome';
        return $row->{$field};
    }

    public function formatautocomplete($content){
        $return = array();

        foreach ($content as $row) {
            $newrow = array();
            $newrow['id'] = $row['id'];
            $newrow['text'] = $this->formatTextValueAutoComplete($row);
            $return[] = $newrow;
        }

        return response()->json($return);
    }
}