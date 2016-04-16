<?php
namespace Cms\Models\Content;

use Cms\Models\Content as OrmModel;
use Cms\Traits\Render;
use Cms\Contracts\Renderable;
use CmsRepository;

class ContentListCategories extends OrmModel implements Renderable
{
    use Render;

    public function render()
    {
        $json = json_decode($this->data);
        $count = 5;
        if (isset($json->count)) {
            $count = intval($json->count);
        }

        $list = CmsRepository::get('category')->remember()->findCategoriesByGroup('blog', $count);

        return view($this->getTemplate('listcategories', $this->template), [
            'list' => $list
        ]);
    }
}