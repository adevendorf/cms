<?php
namespace Cms\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use CmsRepository;
use Cache;

class UrlController extends Controller
{
    const CACHE_EXPIRE = 0.5;


    /**
     * Show a page based on the url slug
     *
     * @param Request $request
     * @param string $url
     *
     * @return mixed
     */
    public function show(Request $request, $url)
    {
        $slug = $this->getSlug($url);

        $route = Cache::get($request->url(), function () use ($request, $slug) {
            $value = CmsRepository::get('route')->findWithPageBySlug($slug);
            Cache::put($request->url(), $value, self::CACHE_EXPIRE);
            return $value;
        });

        abort_if(!$route, 404, 'Page Not Found');

        abort_if($route->page->status != 'published', 404, 'Page Not Available');

        $page = $route->page->render();

        return $page;
    }

    /**
     * Return the lowercase version of the slug
     * or / if it's the homepage
     *
     * @param string $slug
     *
     * @return string
     */
    public function getSlug($slug)
    {
        if ($slug === '/') {
            return 'homepage';
        }

        return strtolower($slug);
    }
}