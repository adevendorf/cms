<?php
namespace Cms\Models\Content;

use Cms\Models\Content as OrmModel;
use Cms\Traits\Render;
use Cms\Contracts\Renderable;
use Cms\Facades\CmsRepository;
use Cache;

class ContentLatestPosts extends OrmModel implements Renderable
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

        $latest = Cache::get('content_latest_posts:'.$count, function () use ($count) {
            $value = CmsRepository::get('blog')->findLatestBlogPosts($count);
            Cache::put('content_latest_posts:'.$count, $value, self::CACHE_EXPIRE);
            return $value;
        });

        return view($this->getTemplate('latestposts', $this->template), [
            'latest' => $latest
        ]);
    }
}