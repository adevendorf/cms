<?php
namespace Cms\Repository;

use Cms\Repository\Base\Repository;
use Cms\Models\Asset;
use Config;
use Auth;

class AssetRepository extends Repository
{
    /**
     * @return Asset
     */
    public function newModel()
    {
        $item = new Asset;
        $item->created_by = $this->getUserId();
        $item->folder = 'recent';
        return $item;
    }

    /**
     * @return Collection
     */
    public function get()
    {
        return Asset::get();
    }

    /**
     * @param $request
     * @return Collection
     */
    public function paginate($request)
    {
        //TODO: set as image or docment or video
        $items = Asset::orderBy('id', 'desc')->where('type', 'image');

        if ($request->input('keyword')) {
//          $items = $items->whereRaw("match(keywords) against (? in boolean mode)", [$$this->request->input('keyword')]);
            $items = $items->where('keywords', $request->input('keyword'));
        }

        $this->count = $request->input('count') ? $request->input('count') : $this->count;

        return $items->paginate(100);
    }

    /**
     * @param $id
     * @return Asset
     * @throws \Exception
     */
    public function findBy($column, $value)
    {
        return Asset::where($column, $value)
            ->firstOrFail();
    }

    /**
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public function destroy($id)
    {
        $item = Asset::findOrFail($id);
        $item->delete();
        return true;
    }
}