<?php
namespace Cms\Render;

use Cms\Render\BaseRender;

class LoginRender extends BaseRender
{
    public function render($content, $page)
    {
        return view($this->template('login', $content->template), [
        ]);
    }

}