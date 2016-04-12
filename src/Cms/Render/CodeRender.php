<?php
namespace Cms\Render;

use Cms\Render\BaseRender;

class CodeRender extends BaseRender
{
    public function render($content)
    {
        return view($this->template('code', '_default'), [
            'attributes' => $this->addStylingToDiv($content, $content->styling),
            'text' => html_entity_decode($content->data)
        ]);
    }

}