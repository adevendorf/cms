<?php
namespace Cms\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Cms\Repository\PageRepository;
use Cms\Repository\RouteRepository;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    protected $repo;
    protected $render;

    public function __construct(PageRepository $repo, RouteRepository $routeRepo)
    {
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

        return $route->page->render();
    }
}