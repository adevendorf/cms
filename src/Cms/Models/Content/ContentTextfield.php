<?php
namespace Cms\Models\Content;

use Cms\Models\Content as OrmModel;
use Cms\Traits\Render;
use Cms\Contracts\Renderable;

class ContentTextField extends OrmModel implements Renderable
{
    use Render;

    public function render()
    {
        return view($this->getTemplate('code', '_default'), [
            'attributes' => $this->addStylingToDiv($content, $content->styling),
            'text' => html_entity_decode($content->data)
        ]);
    }
}