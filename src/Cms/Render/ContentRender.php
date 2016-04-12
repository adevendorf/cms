<?php
namespace Cms\Render;

use Cms\Render\BaseRender;

class ContentRender extends BaseRender
{

    public function render($content, $page)
    {

        if ($content->type == 'Text') {
            $html = $this->renderText($content);
        } else {
           $html = $this->renderType($content, $page);
        }

        return $html;
    }

    public function renderText($content)
    {
        return view($this->template('text', $content->template), [
            'attributes' => $this->addStylingToDiv($content, $content->styling),
            'text' => html_entity_decode($content->data)
        ]);
    }

    public function renderType($content, $page)
    {
        $contentRenderer = "\\Cms\\Render\\". ucfirst($content->type) ."Render";
        $renderer = new $contentRenderer();

        if (method_exists($renderer, 'setup')) {
            $renderer->setup();
        }

        return $renderer->render($content, $page);
    }

}
