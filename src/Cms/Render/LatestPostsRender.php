<?php
namespace Cms\Render;

use Cms\Core\Page\PageRepository;
use Cms\Render\BaseRender;
use Cms\Traits\CmsRepo;


class LatestPostsRender extends BaseRender
{
    use CmsRepo;

    public function render($content, $page)
    {
        $json = json_decode($content['data']);
        $count = intval($json->count);

        $latest = $this->getRepository('page')->remember()->findLatestBlogPosts($count);

        return view($this->template('latestposts', $content->template), [
            'latest' => $latest
        ]);
    }
}