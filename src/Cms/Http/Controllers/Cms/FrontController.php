<?php
namespace Cms\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Cms\Repository\PageRepository;
use Cms\Render\PageRender;
use Cms\Repository\RouteRepository;
use Illuminate\Http\Request;

class FrontController extends Controller
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
        }

        $route = $this->routeRepo->findWithPageBySlug($slug);

        if (!$route || !$route->page || $route->page->status != 'published') abort(404, 'Page Not Found');

        return $this->renderPage($route->page);
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