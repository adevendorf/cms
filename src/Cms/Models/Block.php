<?php
namespace Cms\Models;

use Cms\Models\Eloquent\Block as OrmModel;
use Cms\Traits\Render;
use Cms\Contracts\Renderable;
use CmsRepository;
use Cache;

class Block extends OrmModel implements Renderable
{
    use Render;

    const CACHE_EXPIRE = 0.5;

    public function render($options = [])
    {
        $html = '';

        $block = $this;

        $blockContents = Cache::get('block_content:'.$block->id, function () use ($block) {
            $value = $block->content;
            Cache::put('block_content:'.$block->id, $value, self::CACHE_EXPIRE);
            return $value;
        });

        foreach ($blockContents as $content) {
            if ($content->type === 'Block' && $content->resource_id) {
                $resourceId = $content->resource_id;

                $block = Cache::get('block:'.$resourceId, function () use ($resourceId) {
                    $value = CmsRepository::get('block')->findById($resourceId);
                    Cache::put('block:'.$resourceId, $value, self::CACHE_EXPIRE);
                    return $value;
                });

                if ($block) {
                    $html .= $block->render();
                }

            } else {
                $html .= $content->render();
            }
        }

        return $html;
    }

}
