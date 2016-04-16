<?php
namespace Cms\Models\Content;

use Cms\Models\Content as OrmModel;
use Cms\Traits\Render;
use Cms\Contracts\Renderable;
use Cms\Facades\CmsRepository;

class ContentLatestPosts extends OrmModel implements Renderable
{
    use Render;

    public function render()
    {
        $json = json_decode($this->data);
        $count = 5;
        if (isset($json->count)) {
            $count = intval($json->count);
        }

        $latest = CmsRepository::get('blog')->findLatestBlogPosts($count);

        return view($this->getTemplate('latestposts', $this->template), [
            'latest' => $latest
        ]);
    }
}