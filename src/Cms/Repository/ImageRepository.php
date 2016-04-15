<?php
namespace Cms\Repository;

use Cms\Repository\Base\Repository;
use Cms\Models\ImageData;

class ImageRepository extends Repository
{
    /**
     * @return ImageData
     */
    public function newModel()
    {
        $item = new ImageData;
        $item->created_by = $this->getImageId();
        return $item;
    }

    /**
     * @return Collection
     */
    public function get()
    {
        return ImageData::get();
    }

    /**
     * @param $request
     * @return Collection
     */
    public function paginate($request)
    {
        $items = ImageData::orderBy('id', 'asc');

        $this->count = $request->input('count') ? $request->input('count') : $this->count;

        return $items->paginate($this->count);
    }

    /**
     * @param $id
     * @return Image
     * @throws \Exception
     */
    public function findBy($column, $value)
    {
        return ImageData::with(
            'asset',
            'crops'
            )
            ->where($column, $value)
            ->firstOrFail();
    }

    /**
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public function destroy($id)
    {
        $item = ImageData::findOrFail($id);
        $item->delete();
        return true;
    }

}