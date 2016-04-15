<?php
namespace Cms\Models;

use Cms\Models\Eloquent\Block as OrmModel;
use Cms\Traits\Render;

class Block extends OrmModel  {

    use Render;

    public function render()
    {
        $html = '';

//        $renderer = new ContentRender();

        foreach ($this->content as $c) {
            if ($c->type == 'Block') {
                if ($c->resource_id) {
                    $block = $this->getRepository('block')->remember()->findById($c->resource_id);
                    $html .= $this->render();
                }
            } else {
                 $html .= $c->render();
            }
        }

        return $html;        
    }

}
