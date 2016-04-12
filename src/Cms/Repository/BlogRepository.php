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
}