<?php
namespace Cms\Models;

use Cms\Models\Eloquent\Content as OrmModel;
use Cms\Traits\Render;
use Cms\Contracts\Renderable;

class Content extends OrmModel implements Renderable
{
    use Render;

    public function render()
    {
        $model = '\\Cms\\Models\\' . ucfirst($this->type);
        $model = new $model();
        $model->fill($this->toArray());

        return $model->render();
    }

}
