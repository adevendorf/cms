<?php
namespace Cms\Models\Content;

use Cms\Models\Form as OrmModel;
use Cms\Traits\Render;
use Cms\Contracts\Renderable;

class ContentFeed extends OrmModel implements Renderable
{
    use Render;

    public function render($content, $page, $step = 0)
    {
        if (!$content->resource_id) return '';

        $form = $this->repo->findById($content->resource_id);

        $formFieldRender = new FormFieldRender();
        $contentRenderer = new ContentRender();
        $fields = [];

        foreach($form->blocks[$step]->content as $content) {
            if (isset($content->field)) {
                $fields[] = $formFieldRender->render($content->field);
            } else {
                $fields[] = $contentRenderer->render($content, $page);
            }
        }

        $last = count($form->blocks) == 1 ? true : false;
        $step = 2;

        return view($this->getTemplate('form', '_default'), [
            'form' => $form,
            'fields' => $fields,
            'last' => $last,
            'step' => $step
        ]);
    }

}