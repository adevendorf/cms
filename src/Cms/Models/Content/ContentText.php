<?php
namespace Cms\Models\Content;

use Cms\Models\Content as OrmModel;
use Cms\Traits\Render;
use Cms\Contracts\Renderable;

class ContentText extends OrmModel implements Renderable
{
    use Render;
    
    public function render() 
    {
        return view($this->getTemplate('text', $this->template), [
            'attributes' => $this->addStylingToDiv($this, $this->styling),
            'text' => html_entity_decode($this->data)
        ]);
    }
}