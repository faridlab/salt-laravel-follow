<?php

namespace SaltFollow\Controllers;

use OpenApi\Annotations as OA;
use Illuminate\Support\Facades\Auth;

use SaltLaravel\Controllers\Controller;
use SaltLaravel\Controllers\Traits\ResourceIndexable;
use SaltLaravel\Controllers\Traits\ResourceStorable;
use SaltLaravel\Controllers\Traits\ResourceShowable;
use SaltLaravel\Controllers\Traits\ResourceUpdatable;
use SaltLaravel\Controllers\Traits\ResourcePatchable;
use SaltLaravel\Controllers\Traits\ResourceDestroyable;
use SaltLaravel\Controllers\Traits\ResourceTrashable;
use SaltLaravel\Controllers\Traits\ResourceTrashedable;
use SaltLaravel\Controllers\Traits\ResourceRestorable;
use SaltLaravel\Controllers\Traits\ResourceDeletable;
use SaltLaravel\Controllers\Traits\ResourceImportable;
use SaltLaravel\Controllers\Traits\ResourceExportable;
use SaltLaravel\Controllers\Traits\ResourceReportable;

/**
 * @OA\Info(
 *      title="Countries Endpoint",
 *      version="1.0",
 *      @OA\Contact(
 *          name="Farid Hidayat",
 *          email="farid@startapp.id",
 *          url="https://startapp.id"
 *      )
 *  )
 */
class FollowsResourcesController extends Controller
{
    protected $modelNamespace = 'SaltFollow';

    /**
     * @OA\Get(
     *      path="/api/v1/countries",
     *      @OA\ExternalDocumentation(
     *          description="More documentation here...",
     *          url="https://github.com/faridlab/laravel-search-query"
     *      ),
     *      @OA\Parameter(
     *          in="query",
     *          name="search",
     *          required=false
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="List of Country"
     *      ),
     *      @OA\Response(response="default", description="Welcome page")
     * )
     */
    use ResourceIndexable;

    use ResourceStorable;
    use ResourceShowable;
    use ResourceUpdatable;
    use ResourcePatchable;
    use ResourceDestroyable;
    use ResourceTrashable;
    use ResourceTrashedable;
    use ResourceRestorable;
    use ResourceDeletable;
    use ResourceImportable;
    use ResourceExportable;
    use ResourceReportable;


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function follow(Request $request, $id)
    {
        $this->checkModelAuthorization('follow', 'create');

        try {
            // NOTE: default value follow user
            $request->merge([
                'foreign_table' => 'user',
                'foreign_id' => $id,
                'scope' => 'user'
            ]);

            $validator = $this->model->validator($request);
            if ($validator->fails()) {
                $this->responder->set('errors', $validator->errors());
                $this->responder->set('message', $validator->errors()->first());
                $this->responder->setStatus(400, 'Bad Request.');
                return $this->responder->response();
            }
            $fields = $request->only($this->model->getTableFields());


            foreach ($fields as $key => $value) {
                $this->model->setAttribute($key, $value);
            }

            $this->model->save();
            $this->responder->set('message', Str::title(Str::singular($this->table_name)).' created!');
            $this->responder->set('data', $this->model);
            $this->responder->setStatus(201, 'Created.');
            return $this->responder->response();
        } catch (\Exception $e) {
            $this->responder->set('message', $e->getMessage());
            $this->responder->setStatus(500, 'Internal server error.');
            return $this->responder->response();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function following(Request $request) {

        $this->checkModelAuthorization('following', 'read');

        try {
            $user = Auth::user();

            $count = $this->model->where('created_by', $user->id)->count();
            $model = $this->model->where('created_by', $user->id)->filter();

            $format = $request->get('format', 'default');

            $limit = intval($request->get('limit', 25));
            if($limit > 100) {
                $limit = 100;
            }

            $p = intval($request->get('page', 1));
            $page = ($p > 0 ? $p - 1: $p);

            if($format == 'datatable') {
                $draw = $request['draw'];
            }

            $modelCount = clone $model;
            $meta = array(
                'recordsTotal' => $count,
                'recordsFiltered' => $modelCount->count()?: $count
            );

            $data = $model
                        ->offset($page * $limit)
                        ->limit($limit)
                        ->get();

            $this->responder->set('message', 'Data retrieved.');
            $this->responder->set('meta', $meta);
            $this->responder->set('data', $data);
            if($format == 'datatable') {
                $this->responder->set('draw', $draw);
                $this->responder->set('recordsFiltered', $meta['recordsFiltered']);
                $this->responder->set('recordsTotal', $meta['recordsTotal']);
            }
            return $this->responder->response();
        } catch(\Exception $e) {
            $this->responder->set('message', $e->getMessage());
            $this->responder->setStatus(500, 'Internal server error.');
            return $this->responder->response();
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function followers(Request $request) {

        $this->checkModelAuthorization('followers', 'read');

        try {
            $user = Auth::user();

            $count = $this->model->where('foreign_id', $user->id)->count();
            $model = $this->model->where('foreign_id', $user->id)->filter();

            $format = $request->get('format', 'default');

            $limit = intval($request->get('limit', 25));
            if($limit > 100) {
                $limit = 100;
            }

            $p = intval($request->get('page', 1));
            $page = ($p > 0 ? $p - 1: $p);

            if($format == 'datatable') {
                $draw = $request['draw'];
            }

            $modelCount = clone $model;
            $meta = array(
                'recordsTotal' => $count,
                'recordsFiltered' => $modelCount->count()?: $count
            );

            $data = $model
                        ->offset($page * $limit)
                        ->limit($limit)
                        ->get();

            $this->responder->set('message', 'Data retrieved.');
            $this->responder->set('meta', $meta);
            $this->responder->set('data', $data);
            if($format == 'datatable') {
                $this->responder->set('draw', $draw);
                $this->responder->set('recordsFiltered', $meta['recordsFiltered']);
                $this->responder->set('recordsTotal', $meta['recordsTotal']);
            }
            return $this->responder->response();
        } catch(\Exception $e) {
            $this->responder->set('message', $e->getMessage());
            $this->responder->setStatus(500, 'Internal server error.');
            return $this->responder->response();
        }
    }

}

