<?php
namespace Cms\Models\Content;

use Cms\Models\Content as OrmModel;
use Cms\Traits\Render;
use Cms\Contracts\Renderable;
use CmsRepository;

class ContentGallery extends OrmModel implements Renderable
{
    use Render;

    public function render()
    {
        $block = CmsRepository::get('block')->findById($this->resource_id);

        if (!$block) return '';

        $contents = [];

        foreach ($block->content as $c) {
            $contents[] = $c->render();
        }

        return view($this->getTemplate('gallery', $this->template), [
            'attributes' => $this->addStylingToDiv($this, $this->styling),
            'title' => $this->title,
            'contents' => $contents
        ]);
    }
}