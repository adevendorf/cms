<?php
namespace Cms\Repository;

use Cms\Repository\Base\Repository;
use Cms\Models\User;

class UserRepository extends Repository
{
    /**
     * @return User
     */
    public function newModel()
    {
        $item = new User;
        $item->created_by = $this->getUserId();
        return $item;
    }

    /**
     * @return Collection
     */
    public function get()
    {
        return User::get();
    }

    /**
     * @param $request
     * @return Collection
     */
    public function paginate($request)
    {
        $items = User::orderBy('id', 'asc');

        $this->count = $request->input('count') ? $request->input('count') : $this->count;

        return $items->paginate($this->count);
    }

    /**
     * @param $id
     * @return User
     * @throws \Exception
     */
    public function findBy($column, $value)
    {
        return User::with('image', 'image.asset', 'image.crops')
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
        $item = User::findOrFail($id);
        $item->delete();
        return true;
    }

}