<?php
namespace Cms\Http\Controllers\Cms\Api;

use Illuminate\Http\Request;
use Cms\Repository\RouteRepository;
use Cms\Http\Controllers\Cms\Base\ApiController;

class RouteController extends ApiController
{
    /**
     * RouteController constructor.
     * @param RouteRepository $repo
     */
    public function __construct(RouteRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {

        $route = $this->repo->findById($id);
        $route->fill($request->all());
//        $route->slugify();
//        $route->setPrimaryDir();
        $route->save();

        return $this->show($request, $route->id);
    }
}
