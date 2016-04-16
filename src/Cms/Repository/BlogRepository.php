<?php
namespace Cms\Repository;

use Cms\Repository\Base\Repository;
use Cms\Models\Page;

class BlogRepository extends Repository
{

    public function paginate($count)
    {
        $cacheyKey = $this->createCacheKey('findBy:'.$column.':'.$value);

        if ($this->shouldCache()) {
            $cacheValue = $this->getCache($cacheyKey);

            if ($cacheValue) return $cacheValue;
        }


        if ($this->shouldCache()) {
            $this->putCache($cacheyKey, $page, ['blog']);
        }

        return $page;
    }

    public function findLatestBlogPosts($count, $categoryId = false)
    {
        $pages = Page::where('type', 'blog')
            ->where('status', 'published')
            ->with(
                'author',
                'section',
                'category'
            )
            ->orderBy('created_at', 'desc');

        if ($categoryId) {
            $pages = $pages->where('category_id', $categoryId);
        }

        $pages = $pages->paginate($count);

        return $pages;
    }
}