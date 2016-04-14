<?php

namespace Cms\Repository;

use Cms\Repository\Base\Repository;
use Cms\Models\Page;
use Config;
use Carbon\Carbon;
use Cache;

class PageRepository extends Repository
{
    /**
     * @return Section
     */
    public function newModel()
    {
        $item = new Page;
        $item->created_by = $this->getUserId();
        return $item;
    }

    public function paginate($request)
    {
        $items = Page::where('id', '>', 0);


        $this->count = $request->input('count') ? $request->input('count') : $this->count;

        $items = $request->input('type') ? $items->where('type', $request->input('type')) : $items->where('type', 'page');

//        $items = $request->input('title') ? $items->whereRaw("MATCH(title) AGAINST(? IN BOOLEAN MODE)", [$request->input('title')]) : $items;
        $items = $request->input('title') ? $items->where('title', 'LIKE', '%'.$request->input('title').'%') : $items;

        $items = $request->input('status') ? $items->where('status', $request->input('status')) : $items;

        $items = $request->input('section_id') ? $items->where('section_id', $request->input('section_id')) : $items;

        $items = $request->input('category_id') ? $items->where('category_id', $request->input('category_id')) : $items;

        $items = $request->input('type') == 'blog' ? $items->with('category') : $items;

        $items = (!$request->input('order_by') || $request->input('order_by') == 'last_edit') ? $items->orderBy('updated_at', 'DESC') : $items;

        $items = $request->input('order_by') == 'id' ? $items->orderBy('id', 'ASC') : $items;

        $items = $request->input('order_by') == 'slug' ? $items->orderBy('slug', 'ASC') : $items;

        $items = $request->input('order_by') == 'created_at' ? $items->orderBy('created_at', 'ASC') : $items;

        $items = $items->with(
            'route',
            'section'
        )->paginate($this->count);


////        if ($request->input('fields')) {
//            $items = $this->limitToFields($items, $request->input('fields'));
////        }

        return $items;
    }

    public function findBy($column, $value)
    {
        $page = Page::with(
                'blocks',
                'route',
                'image',
                'image.crops',
                'image.asset',
                'author',
                'section'
            )
            ->where($column, $value)
            ->firstOrFail();

        return $page;
    }

    public function findLatestBlogPosts($count, $categoryId = false)
    {
        $pages = Page::where('type', 'blog')
            ->where('status', 'published')
            ->with(
//                'image',
//                'image.crops',
//                'image.asset',
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


    public function findLastEdited()
    {
        $pages = Page::orderBy('updated_at', 'desc')->take(10)->get();
        return $pages;
    }

    public function findUpcomingPublishing()
    {
        $pages = Page::where('scheduled_publish', '!=', '')->orderBy('updated_at', 'desc')->take(10)->get();
        return $pages;
    }




    public function destroy($id)
    {
        $item = Page::findOrFail($id);
        $item->delete();
        return true;
    }

//    public function findExistingSlug($id, $slug, $sectionId)
//    {
//        $page = Page::where('slug', $slug)
//            ->where('section_id', $sectionId)
//            ->where('id', '!=', $id)
//            ->get();
//
//        return $page;
//    }

    public function publishScheduled()
    {
        $now = Carbon::now();
        $pages = Page::where('status', 'scheduled')
            ->where('scheduled_publish', '<', $now)
            ->get();
        foreach ($pages as $page) {
            $page->publish();
        }

        return $pages;
    }

    public function unpublishScheduled()
    {
        $now = Carbon::now();
        $pages = Page::where('status', 'published')
            ->where('scheduled_unpublish', '<', $now)
            ->get();
        foreach ($pages as $page) {
            $page->unpublish();
        }

        return $pages;
    }
}