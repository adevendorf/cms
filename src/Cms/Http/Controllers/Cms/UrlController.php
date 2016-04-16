<?php
namespace Cms\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use CmsRepository;

/**
 * Class UrlController
 * @package Cms\Http\Controllers\Cms
 */
class UrlController extends Controller
{
    /**
     * @param $url
     * @return mixed
     */
    public function show($url) {
        $slug = $this->getSlug($url);

        $route = CmsRepository::get('route')->findWithPageBySlug($slug);

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