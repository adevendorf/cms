<?php
namespace Cms\Models;

use Cms\Models\Eloquent\Block as OrmModel;
use Cms\Traits\Render;
use Cms\Contracts\Renderable;

class Block extends OrmModel implements Renderable
{
    use Render;

    public function render()
    {
        $html = '';

        foreach ($this->content as $c) {
            if ($c->type == 'Block') {
                if ($c->resource_id) {
                    $block = $this->getRepository('block')->findById($c->resource_id);
                    $html .= $block->render();
                }
            } else {
                 $html .= $c->render();
            }
        }

        return $html;        
    }

}
