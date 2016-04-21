<?php
namespace Cms\Models\Content;

use Cms\Models\Content as OrmModel;
use Cms\Traits\Render;
use Cms\Contracts\Renderable;

class ContentColumnContainer extends OrmModel implements Renderable
{
    use Render;

    public function render($options = [])
    {
       return 'columns';
    }
}