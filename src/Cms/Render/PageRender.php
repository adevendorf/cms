<?php
namespace Cms\Render;

use Cms\Managers\ImageManager;
use Cms\Render\BaseRender;
use Cms\Render\BlockRender;
use Cms\Traits\Meta;

class PageRender extends BaseRender
{
    use Meta;

    protected $page;
    protected $render;
    protected $imageManager;

    public function __construct(BlockRender $render, ImageManager $imageManager)
    {
        $this->render = $render;
        $this->imageManager = $imageManager;
    }

    public function setPage($page)
    {
        $this->page = $page;
    }

    public function render()
    {
        if ($this->page->type == 'blog') {
            $template = 'cms.themes.' . config('cms.theme') . '.blog.blog_post';
        } else {
            if ($this->page->template_name == '') {
                $template = 'cms.themes.' . config('cms.theme') . '.page.default';
            } else {
                $template = 'cms.themes.' . config('cms.theme') . '.page.' . $this->page->template_name;
            }
        }

        $meta = $this->buildMetas($this->page);

        $renderer = $this->render;
        $blocks = [];

        foreach ($this->page->blocks as $block) {
            $html = $renderer->render($block->content, $this->page);
            $blocks[$block->title] = $html;
        }

        return view($template, [
            'page' => $this->page,
            'content' => $blocks,
            'meta' => $meta
        ]);
    }
}