<?php

namespace Devguar\OContainer\Controllers;

use App\Http\Controllers\Controller as OriginalController;
use Devguar\OContainer\Permissions\PermissionsControl;
use Devguar\OContainer\Repositories\Criteria\Miscellaneous\FiltroFalso;
use Illuminate\Http\Request;
use Devguar\OContainer\Repositories\Criteria\BootstrapTable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Exception;

abstract class SimpleCrudController extends OriginalController
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
    public function getRepository()
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
        return $this->loadViewIndex();
    }

    public function loadViewIndex(){
        return view($this->viewsfolder.'.index');
    }

    public function listcontent(){
        try{
            $search = isset($_GET['search']) ? $_GET['search'] : null;
            $sort = isset($_GET['sort']) ? $_GET['sort'] : null;
            $order = isset($_GET['order']) ? $_GET['order'] : null;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : null;
            $offset = isset($_GET['offset']) ? $_GET['offset'] : null;

            $this->repository->pushCriteria(new FiltroFalso());

            $this->repository->pushCriteria(new BootstrapTable\Select());
            $this->repository->pushCriteria(new BootstrapTable\Joins());
            $this->repository->pushCriteria(new BootstrapTable\Search($search));
            $this->repository->pushCriteria(new BootstrapTable\Order($sort, $order));

            $return = new \stdClass();
            $return->total = $this->repository->all()->count();

            $this->repository->pushCriteria(new BootstrapTable\Pagination($limit, $offset));
            $return->rows = $this->repository->all();

            $return->success = true;

            return $this->formatlistcontent($return);
        }catch(Exception $e){
            \Log::error(\Route::getCurrentRoute()->getActionName(), ['message' => $e->getMessage(), 'trace' => $e->getTrace()]);
            return $e->getMessage();
        }
    }

    public function formatlistcontent($content){
        return response()->json($content);
    }

    public function create()
    {
        try{
            return $this->loadViewCreate();
        }catch(Exception $e){
            \Log::error(\Route::getCurrentRoute()->getActionName(), ['message' => $e->getMessage(), 'trace' => $e->getTrace()]);
            return view('errors.custom')->withException($e);
        }
    }

    public function loadViewCreate(){
        $object = $this->repository->getModel();
        return view($this->viewsfolder.'.create-edit')->withObject($object);
    }

    public function edit($id)
    {
        try{
            $object = $this->repository->find($id);

            if (!isset($object->id))
                throw new Exception("Registro não encontrada para edição.");

            return $this->loadViewEdit($object);
        }catch(Exception $e){
            \Log::error(\Route::getCurrentRoute()->getActionName(), ['message' => $e->getMessage(), 'trace' => $e->getTrace()]);
            return view('errors.custom')->withException($e);
        }
    }

    public function loadViewEdit($object){
        return view($this->viewsfolder.'.create-edit')->withObject($object);
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
        try{
            $object = $this->repository->find($id);

            if (!isset($object->id))
                throw new Exception("Registro não encontrada para exclusão.");

            return $this->doDelete($id);
        }catch(Exception $e){
            \Log::error(\Route::getCurrentRoute()->getActionName(), ['message' => $e->getMessage(), 'trace' => $e->getTrace()]);

            if ($e->getCode() == 23000){
                $e->descricao = 'Não é possível excluir um registro que está sendo utilizado em outros lugares do sistema.';
                return view('errors.custom')->withException($e);
            }else{
                return view('errors.custom')->withException($e);
            }
        }
    }

    public function autocomplete(){
        try{
            $termo = (isset($_GET["termo"]) ? $_GET["termo"] : null);

            $this->repository->pushCriteria(new FiltroFalso());

            $this->repository->pushCriteria(new BootstrapTable\Select());
            $this->repository->pushCriteria(new BootstrapTable\Joins());
            $this->repository->pushCriteria(new BootstrapTable\Search($termo));

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