<?php
namespace Cms\Repository;

use Cms\Repository\Base\Repository;
use Cms\Models\Section;
use Config;
use Auth;

class SectionRepository extends Repository
{
    /**
     * @return Section
     */
    public function newModel()
    {
        $item = new Section;
        $item->created_by = $this->getUserId();
        return $item;
    }

    /**
     * @return Collection
     */
    public function get()
    {
        return Section::get();
    }

    /**
     * @param $request
     * @return Collection
     */
    public function paginate($request)
    {
        $items = Section::orderBy('id', 'asc');

//        $items = $request->input('group') ? $items->where('group', $request->input('group')) : $items;

        if ($request->input('count')) {
            $this->count = $request->input('count') ? $request->input('count') : $this->count;
            return $items->paginate($this->count);
        }

        return $items->get();;
    }

    /**
     * @param $id
     * @return Section
     * @throws \Exception
     */
    public function findBy($column, $value)
    {
        return Section::with('image', 'image.asset', 'image.crops')
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
        $item = Section::findOrFail($id);
        $item->delete();
        return true;
    }
}