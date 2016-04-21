<?php
namespace Cms\Models\Content;

use Cms\Models\Content as OrmModel;
use Cms\Traits\Render;
use Cms\Contracts\Renderable;
use CmsRepository;
use Cache;

class ContentGallery extends OrmModel implements Renderable
{
    use Render;

    const CACHE_EXPIRE = 0.5;

    public function render($options = [])
    {
        $resourceId = $this->resource_id;

        $block = Cache::get('gallery:'.$resourceId, function() use($resourceId) {
            $value = CmsRepository::get('block')->findById($this->resource_id);
            Cache::put('gallery:'.$resourceId, $value, self::CACHE_EXPIRE);
            return $value;
        });


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