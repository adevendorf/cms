<?php
namespace Cms\Http\Controllers\Cms;

use Cms\Http\Controllers\Cms\Base\CmsController;
use CmsRepository;


/**
 * Class BlogController
 * @package Cms\Http\Controllers
 */
class BlogController extends CmsController
{

    /**
     * @param $year
     * @param $month
     * @param $day
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPost($year, $month, $day, $slug)
    {
        $route = CmsRepository::get('route')->findBy('url', $slug);

        abort_if(!$route || $route->page->status != 'published', 404, 'Page Not Found');

        return $route->page->render();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex()
    {
        $posts = CmsRepository::get('blog')->findLatestBlogPosts(10);

        return view('cms.themes.default.blog.blog_index', [
            'posts' => $posts,
        ]);
    }

    /**
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Cms\Traits\Exception
     */
    public function getCategory($slug)
    {
        $posts = [];
        $category = CmsRepository::get('category')->findBy('slug', $slug);

        abort_if(!$category, 404, 'Page Not Found');

        $posts = $CmsRepository::get('blog')->findLatestBlogPosts(10, $category->id);

        return view('cms.themes.default.blog.blog_index', [
            'posts' => $posts,
        ]);
    }
}