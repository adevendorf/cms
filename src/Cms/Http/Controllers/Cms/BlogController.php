<?php

namespace Cms\Http\Controllers\Cms;

use Cms\Http\Controllers\Cms\Base\CmsController;
use Cms\Repository\PageRepository;
use Cms\Models\Category;
use Cms\Models\Page;
use Cms\Repository\RouteRepository;
use Cms\Traits\Meta;
use Cms\Render\PageRender;
use Illuminate\Http\Requestl;


/**
 * Class BlogController
 * @package Cms\Http\Controllers
 */
class BlogController extends CmsController
{
    /**
     * @var PageRender
     */
    protected $render;

    /**
     * BlogController constructor.
     * @param PageRepository $repo
     * @param PageRender $render
     */
    public function __construct(PageRepository $repo, PageRender $render, RouteRepository $routeRepo)
    {
        $this->repo = $repo;
        $this->render = $render;
        $this->routeRepo = $routeRepo;
    }

    /**
     * @param $page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function renderPage($page)
    {
        $this->render->setPage($page);
        return $this->render->render();
    }

    /**
     * @param $year
     * @param $month
     * @param $day
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPost($year, $month, $day, $slug)
    {
        $route = $this->routeRepo->findBy('url', $slug);
        if (!$route) abort(404, 'Page Not Found');

        $post = $this->repo->findById($route->page_id);

        if (!$post || $post->status != 'published') abort(404, 'Page Not Found');

        return $this->renderPage($post);

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex()
    {
        $posts = $this->repo->remember()->findLatestBlogPosts(10);

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
        $category = $this->getRepository('category')->remember()->findBy('slug', $slug);

        if ($category) {
            $posts = $this->repo->remember()->findLatestBlogPosts(10, $category->id);
        }

        return view('cms.themes.default.blog.blog_index', [
            'posts' => $posts,
        ]);
    }
}