<?php
namespace Cms\Models\Content;

use Cms\Models\Content as OrmModel;
use Cms\Traits\Render;
use Cms\Contracts\Renderable;

class ContentFormField extends OrmModel implements Renderable
{
    use Render;

    public function render($content)
    {

        return view($this->getTemplate('form-' . strtolower($content->question_type), '_default'), [
            'data' => $content
        ]);
    }
}