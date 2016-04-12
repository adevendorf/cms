<?php
namespace Cms\Render;

use Cms\Render\BaseRender;

class RegisterRender extends BaseRender
{
    public function render($content, $page)
    {
         return view($this->template('register', $content->template), [
        ]);
    }

}