<?php
namespace Cms\Repository;

use Illuminate\Http\Request;
use Cms\Models\NewsFeed;
use Cms\Repository\Base\Repository;
use Config;

class NewsFeedRepository extends Repository
{

    public function newModel()
    {
        $item = new NewsFeed;
        $item->created_by = $this->getUserId();
        return $item;
    }

    public function findBySection(Request $request, $id)
    {
        $items = NewsFeed::orderBy('order', 'ASC')
            ->with(
                'page',
                'image',
                'image.asset',
                'image.crops'
            )
            ->where('section_id', $id)->get();

        return $items;
    }


    public function findBy($column, $value)
    {
        return NewsFeed::with(
            'page',
            'image',
            'image.asset',
            'image.crops'
        )
            ->where($column, $value)
            ->firstOrFail();
    }

    public function destroy($id)
    {
        $item = NewsFeed::findOrFail($id);
        $item->delete();
        return true;
    }

}