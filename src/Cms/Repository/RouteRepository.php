<?php
namespace Cms\Repository;

use Cms\Repository\Base\Repository;
use Cms\Models\Route;

/**
 * Class RouteRepository
 * @package Cms\Repository
 */
class RouteRepository extends Repository
{
    /**
     * @return Route
     */
    public function newModel()
    {
        $item = new Route;
        $item->created_by = $this->getUserId();
        return $item;
    }

    public function findWithPageBySlug($slug)
    {
        return Route::where('url', $slug)
            ->with('page')
            ->firstOrFail();
    }

    public function findBy($column, $value)
    {
        $item = Route::where($column, $value)
            ->firstOrFail();

        return $item;
    }


}