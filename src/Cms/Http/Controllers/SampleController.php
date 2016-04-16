<?php
namespace Cms\Http\Controllers;

use Cms\Repository\PageRepository;
use Cms\Http\Controllers\Cms\Base\CmsController;
use Illuminate\Http\Request;
use CmsRepository;

class SampleController extends CmsController
{
    public function getSamplePage(Request $request)
    {
        $route = CmsRepository::get('route')->findBy('slug', 'sample');

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