<?php
namespace Cms\Models\Content;

use Cms\Models\NewsFeed as OrmModel;
use Cms\Traits\Render;
use Cms\Contracts\Renderable;

class ContentFeed extends OrmModel implements Renderable
{
    use Render;


    public function render($content, $page)
    {
        $renderer = new ImageRender();

        $data = json_decode($content->data);

        if (!$data) return '';


        $feed = NewsFeed::where('section_id', $data->id)
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
                'image' => $item->custom_image ? $item : $item->page,
                'new_window' => $item->new_window ? true : false
            ];
        }

        $contents = $this->multiArrayToArrayOfObjects($contents);

        return view($this->getTemplate('feed', $content->template), [
            'attributes' => $this->addStylingToDiv($content, $content->styling),
            'contents' => $contents
        ]);
    }


}