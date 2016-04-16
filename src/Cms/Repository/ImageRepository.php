<?php
namespace Cms\Repository;

use Cms\Repository\Base\Repository;
use Cms\Models\Image;

class ImageRepository extends Repository
{
    /**
     * @return Image
     */
    public function newModel()
    {
        $item = new Image;
        $item->created_by = $this->getImageId();
        return $item;
    }

    /**
     * @return Collection
     */
    public function get()
    {
        return Image::get();
    }

    /**
     * @param $request
     * @return Collection
     */
    public function paginate($request)
    {
        $items = Image::orderBy('id', 'asc');

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
        return Image::with(
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
        $item = Image::findOrFail($id);
        $item->delete();
        return true;
    }

}