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

        $items = $request->input('title') ? $items->where('title', 'LIKE', '%'.$request->input('title').'%') : $items;

        $items = $request->input('status') ? $items->where('status', $request->input('status')) : $items;

        $items = $request->input('section_id') ? $items->where('section_id', $request->input('section_id')) : $items;

        $items = $request->input('category_id') ? $items->where('category_id', $request->input('category_id')) : $items;

        $items = (!$request->input('order_by') || $request->input('order_by') == 'last_edit') ? $items->orderBy('updated_at', 'DESC') : $items;

        $items = $request->input('order_by') == 'id' ? $items->orderBy('id', 'ASC') : $items;

        $items = $request->input('order_by') == 'slug' ? $items->orderBy('slug', 'ASC') : $items;

        $items = $request->input('order_by') == 'created_at' ? $items->orderBy('created_at', 'ASC') : $items;

        $items = $items->with('route');

        if ($request->input('count')) {
            $this->count = $request->input('count') ? $request->input('count') : $this->count;
            return $items->paginate($this->count);
        }

        return $items->get();;
    }

    public function findBy($column, $value)
    {
        $page = Page::with(
            'image',
            'image.crops',
            'image.asset'
        )
            ->where($column, $value)
            ->firstOrFail();

        return $page;
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