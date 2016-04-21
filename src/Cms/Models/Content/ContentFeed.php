<?php
namespace Cms\Models\Content;

use Cms\Models\Content as OrmModel;
use Cms\Traits\Render;
use Cms\Contracts\Renderable;
use Cms\Models\NewsFeed;
use Cache;

class ContentFeed extends OrmModel implements Renderable
{
    use Render;
    const CACHE_EXPIRE = 0.5;

    protected $casts = [
        'styling' => 'array',
        'data' => 'array',
    ];

    public function render($options = [])
    {
        $data = json_decode($this->data);

        if (!$data) return '';

        $sectionId = $data->id;

        $feed = Cache::get('feed:'.$sectionId, function() use($sectionId) {
            $value = NewsFeed::where('section_id', $sectionId)
                ->where('active', 1)
                ->orderBy('order', 'ASC')
                ->with(
                    'page',
                    'page.image',
                    'page.image.asset',
                    'page.image.crops',
                    'image',
                    'image.asset',
                    'image.crops'
                )
                ->get();
            Cache::put('feed:'.$sectionId, $value, self::CACHE_EXPIRE);
            return $value;
        });


        if (!$feed) return '';

        $feed = $feed->reject(function($item) {
            return $item->page->status != 'published';
        });

        $contents = [];

        foreach($feed as $item) {

            $contents[] = [
                'title' => $item->custom_headline ? $item->headline : $item->page->title,
                'synopsis' => $item->custom_synopsis ? $item->synopsis : $item->page->synopsis,
                'path' => $item->custom_url ? $item->redirect_url : '/' . $item->page->slug,
                'image' => $item->custom_image ? $item->image : $item->page->image,
                'new_window' => $item->new_window ? true : false
            ];
        }

        $contents = $this->multiArrayToArrayOfObjects($contents);

        return view($this->getTemplate('feed', $this->template), [
            'attributes' => $this->addStylingToDiv($this, $this->styling),
            'contents' => $contents
        ]);
    }


}