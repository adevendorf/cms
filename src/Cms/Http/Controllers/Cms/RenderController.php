<?php

namespace Cms\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Cms\Repository\PageRepository;
use Cms\Render\PageRender;
use Cms\Repository\RouteRepository;
use Illuminate\Http\Request;
use Cms\Models\Page;
use Cache;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Guzzle\Http\Exception\BadResponseException;

class RenderController extends Controller
{
    protected $repo;
    protected $render;

    public function __construct(PageRender $render, PageRepository $repo, RouteRepository $routeRepo)
    {
        $this->render = $render;
        $this->repo = $repo;
        $this->routeRepo = $routeRepo;
    }

    public function getPage($slug) {
        $slug = strtolower($slug);


        if ($slug == '/') {
            $slug = 'homepage';
        } else {
            $slug = explode('/', $slug);
            $section = head($slug);
        }

        $route = $this->routeRepo->findBy('url', $slug);
        if (!$route) abort(404, 'Page Not Found');

        $page = $this->repo->findById($route->page_id);



        if (!$page || $page->status != 'published') abort(404, 'Page Not Found');

        return $this->renderPage($page);
    }

    public function getTemplate($page)
    {
        if ($page->type == 'blog') {
            $template = 'cms.themes.' . config('cms.theme') . '.blog.blog_post';
        } else {
            if ($page->template_name == '') {
                $template = 'cms.themes.' . config('cms.theme') . '.page.generic';
            } else {
                $template = 'cms.themes.' . config('cms.theme') . '.page.' . $page->template_name;
            }
        }

        return $template;
    }


    public function renderPage($page)
    {
        $this->render->setPage($page);
        return $this->render->render();
    }
}