<?php
namespace Cms\Render;

use Cms\Render\BaseRender;
use Cms\Render\FormFieldRender;
use Cms\Render\ContentRender;
use Cms\Repository\FormRepository;

class FormRender extends BaseRender
{
    protected $repo;

    public function setup()
    {
        $this->repo = new FormRepository();
    }
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

        return view($this->template('form', '_default'), [
            'form' => $form,
            'fields' => $fields,
            'last' => $last,
            'step' => $step
        ]);
    }
}