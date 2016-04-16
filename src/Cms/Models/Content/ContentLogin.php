<?php
namespace Cms\Models\Content;

use Cms\Models\Content as OrmModel;
use Cms\Traits\Render;
use Cms\Contracts\Renderable;

class ContentLogin extends OrmModel implements Renderable
{
    use Render;

    public function render()
    {
        return view($this->getTemplate('login', $content->template), [
        ]);
    }
}