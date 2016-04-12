<?php
namespace Cms\Repository;

use Cms\Models\CropSize;
use Cms\Repository\Base\Repository;

class CropSizeRepository extends Repository
{
    /**
     * @return Asset
     */
    public function newModel()
    {
        $item = new CropSize();
        $item->created_by = $this->getUserId();
        return $item;
    }

    /**
     * @return Collection
     */
    public function get()
    {
        return CropSize::get();
    }

    public function paginate($request)
    {
        //TODO: set as image or docment or video
        $items = CropSize::where('id', '>', 0);

        $this->count = $request->input('count') ? $request->input('count') : $this->count;

        return $items->paginate($this->count);
    }

    public function findBy($column, $value)
    {
        return CropSize::where($column, $value)
            ->firstOrFail();
    }

    public function destroy($id)
    {
        $block = CropSize::findOrFail($id);
        $block->delete();
        return true;
    }
}