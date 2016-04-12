<?php
namespace Cms\Http\Controllers\Cms\Base;

use Cms\Http\Controllers\Cms\Base\CmsController;
use Illuminate\Http\Request;

/**
 * Class ApiController
 * @package Cms\Http\Controllers\Cms\Base
 */
class ApiController extends CmsController
{
    /**
     * Return a paginated collection with no options
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request) {
        return $this->repo->paginate($request);
    }

    /**
     * Find a record by Id
     *
     * @param $id
     * @return mixed
     */
    public function show(Request $request, $id) {
        return $this->repo->findById($id);
    }

    /**
     * Delete a record by Id
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id) {
        $this->repo->destroy($id);
        return response()->json(null, 200);
    }
}