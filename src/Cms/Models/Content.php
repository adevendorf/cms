<?php
namespace Cms\Models;

use Cms\Models\Eloquent\Content as OrmModel;
use Cms\Traits\Render;

class Content extends OrmModel
{
    use Render;

    public function render()
    {
        if ($this->type == 'Text') {
            return $this->renderText();
        }

        return $this->renderType();
    }

    public function renderText()
    {
        return view($this->template('text', $this->template), [
            'attributes' => $this->addStylingToDiv($this, $this->styling),
            'text' => html_entity_decode($this->data)
        ]);
    }

    public function renderType()
    {
        $contentRenderer = "\\Cms\\Render\\". ucfirst($this->type) ."Render";
        $renderer = new $contentRenderer();

        if (method_exists($renderer, 'setup')) {
            $renderer->setup();
        }

        return $renderer->render($this);
    }
}
