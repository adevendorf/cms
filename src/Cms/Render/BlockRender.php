<?php
namespace Cms\Render;

use Cms\Render\BaseRender;
use Cms\Render\ContentRender;
use Cms\Models\Block;
use Cms\Repository\BlockRepository;
use Cms\Traits\CmsRepo;

class BlockRender extends BaseRender
{
    use CmsRepo;

    public function render($content, $page, $skipArray = [], $skipAfterArray = [])
    {
        $html = '';

        $renderer = new ContentRender();

        foreach ($content as $c) {
            if ($c->type == 'Block') {
                if ($c->resource_id) {
                    $block = $this->getRepository('block')->remember()->findById($c->resource_id);
                    $html .= $this->render($block->content, $page);
                }
            } else {
                if (!in_array($c->type, $skipArray)) $html .= $renderer->render($c, $page);
            }

            if (in_array($c->type, $skipAfterArray)) break;
        }

        return $html;
    }
}