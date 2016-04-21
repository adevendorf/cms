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
            ->with(
                'page',
                'page.blocks'
            )
            ->firstOrFail();
    }

    public function findBy($column, $value, $primary = null)
    {
        $item = Route::where($column, $value);

        if ($primary) {
            $item = $item->where('primary_dir', $primary);
        }
        
        $item = $item->firstOrFail();

        return $item;
    }

    public function findForPages($pages)
    {
        $item = Route::whereIn('page_id', $pages)
            ->get();
        return $item;
    }


}