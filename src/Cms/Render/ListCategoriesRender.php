<?php
namespace Cms\Render;

use Cms\Render\BaseRender;
use Cms\Repository\CategoryRepository;
use Cms\Traits\CmsRepo;

class ListCategoriesRender extends BaseRender
{
    use CmsRepo;

    public function render($content)
    {
        $json = json_decode($content['data']);
        $count = 10;

        if (isset($json->count)) {
            $count = intval($json->count);
        }

        $list = $this->getRepository('category')->remember()->findCategoriesByGroup('blog', $count);

        return view($this->template('listcategories', $content->template), [
            'list' => $list
        ]);
    }
}