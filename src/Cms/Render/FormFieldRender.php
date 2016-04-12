<?php
namespace Cms\Render;

use Cms\Render\BaseRender;

class FormFieldRender extends BaseRender
{
    public function render($content) {

        return view($this->template('form-' . strtolower($content->question_type), '_default'), [
            'data' => $content
        ]);
    }
}