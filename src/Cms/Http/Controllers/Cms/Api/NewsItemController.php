<?php

namespace Cms\Http\Controllers\Cms\Api;

use Illuminate\Http\Request;
use Cms\Models\NewsFeed;
use Cms\Repository\NewsFeedRepository;
use Cms\Http\Controllers\Cms\Base\ApiController;


/**
 * Class NewsItemController
 * @package Cms\Http\Controllers\Cms\Api
 */
class NewsItemController extends ApiController
{

    /**
     * NewsItemController constructor.
     * @param NewsFeedRepository $repo
     */
    public function __construct(NewsFeedRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Update the record
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $item = $this->repo->findById($id);

        if ($request->input('field') && $request->input('data')) {

            $field = $request->input('field');
            $item->$field = $request->input('data');
            $item->save();
            return response()->json(null, 200);

        } else {
            $item->fill($request->all());

            $image = $request->input('image');
//            $crops = $request->input('image.crops');

            if ($request->input('image')) {
                $image = $this->imageManager->updateOrCreate($request, isset($image->id) ? $image->id : false);
                $item->image_id = $image->id;
            }
        }

        $item->save();

        return $item;
    }

    /**
     * Save the ordering the items in the collection.
     * TODO: do this with one query?
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reorder(Request $request)
    {
        $json = $request->input('items');
        foreach($json as $item) {
            NewsFeed::where('id', intval($item['id']))->update(['order' => intval($item['order'])]);
        }

        return response()->json(null, 200);
    }
}
