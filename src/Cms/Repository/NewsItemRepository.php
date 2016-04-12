<?php
namespace Cms\Repository;

use Cms\Repository\Base\Repository;

class NewsItemRepository extends Repository
{
    public function newModel()
    {
        $item = new NewsFeed;
        $item->created_by = $this->getUserId();
        return $item;
    }
}