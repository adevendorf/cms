<?php
namespace Cms\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use CmsRepository;
use Cache;

/**
 * Class UrlController
 * @package Cms\Http\Controllers\Cms
 */
class UrlController extends Controller
{
    const CACHE_EXPIRE = 0.5;

    /**
     * @param $url
     * @return mixed
     */
    public function show(Request $request, $url) {
        $slug = $this->getSlug($url);

        $route = Cache::get($request->url(), function() use($request, $slug) {
            $value = CmsRepository::get('route')->findWithPageBySlug($slug);
            Cache::put($request->url(), $value, self::CACHE_EXPIRE);
            return $value;
        });

        abort_if(!$route || $route->page->status != 'published', 404, 'Page Not Found');

        return $route->page->render();
    }

    /**
     * @param $slug
     * @return string
     */
    public function getSlug($slug)
    {
        if ($slug == '/') return 'homepage';

        return strtolower($slug);
    }
}