<?php
namespace Cms\Render;

;
use Cms\Render\BaseRender;
use Cms\Render\ContentRender;
use Cms\Repository\BlockRepository;


class GalleryRender extends BaseRender
{
    protected $repo;

    public function setup()
    {
        $this->repo = new BlockRepository();
        $this->repo->setType('gallery');
    }

    public function render($content, $page)
    {
        $renderer = new ContentRender();

        if (!$content->resource_id) return '';

        $collection = $this->repo->findById($content->resource_id);

        if (!$collection) return '';

        $contents = [];
        foreach ($collection->content as $c) {
            $contents[] = $renderer->render($c, $page);
        }

        return view($this->template('gallery', $content->template), [
            'attributes' => $this->addStylingToDiv($content, $content->styling),
            'title' => $content->title,
            'contents' => $contents
        ]);
    }
}