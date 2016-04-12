<?php
namespace Cms\Http\Controllers;

use Cms\Repository\PageRepository;
use Cms\Http\Controllers\Cms\Base\CmsController;
use Illuminate\Http\Request;
use Cms\Render\BlockRender;

class SampleController extends CmsController
{
    protected $repo;
    protected $renderer;

    public function __construct(PageRepository $repo, BlockRender $render)
    {
        $this->repo = $repo;
        $this->renderer = $render;
    }

    public function getSamplePage(Request $request)
    {
        $page = $this->repo->findBy('slug', 'sample');

        $blocks = [];

        foreach ($page->blocks as $block) {
            $html = $this->renderer->render($block->content, $page);
            $blocks[$block->title] = $html;
        }

        return view('cms.themes.' . config('cms.theme') . '.page.sample', [
            'page' => $page,
            'content' => $blocks
        ]);
    }
}