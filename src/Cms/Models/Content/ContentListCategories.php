<?php
namespace Cms\Models\Content;

use Cms\Models\Content as OrmModel;
use Cms\Traits\Render;
use Cms\Contracts\Renderable;
use CmsRepository;
use Cache;

class ContentListCategories extends OrmModel implements Renderable
{
    use Render;
    const CACHE_EXPIRE = 0.5;

    protected $casts = [
        'styling' => 'array',
        'data' => 'array',
    ];

    public function render($options = [])
    {
        $json = json_decode($this->data);
        $count = 5;
        if (isset($json->count)) {
            $count = intval($json->count);
        }

        $list = Cache::get('content_list_categories:'.$count, function() use($count) {
            $value = CmsRepository::get('category')->findCategoriesByGroup('blog', $count);
            Cache::put('content_list_categories:'.$count, $value, self::CACHE_EXPIRE);
            return $value;
        });

        return view($this->getTemplate('listcategories', $this->template), [
            'list' => $list
        ]);
    }
}