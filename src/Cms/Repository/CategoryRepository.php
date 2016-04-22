<?php
namespace Cms\Repository;

use Cms\Repository\Base\Repository;
use Cms\Models\Category;
use Config;
use Auth;

/**
 * Class CategoryRepository
 * @package Cms\Repository
 */
class CategoryRepository extends Repository
{
    /**
     * @return Category
     */
    public function newModel()
    {
        $item = new Category;
        $item->created_by = $this->getUserId();
        return $item;
    }

    /**
     * @return Collection
     */
    public function get()
    {
        return Category::where('group', 'blog')
            ->get();
    }

    /**
     * @param $request
     * @return Collection
     */
    public function paginate($request)
    {
        $items = Category::orderBy('id', 'asc');

        $items = $request->input('group') ? $items->where('group', $request->input('group')) : $items;

        $this->count = $request->input('count') ? $request->input('count') : $this->count;

        if ($request->input('count')) {
            $this->count = $request->input('count') ? $request->input('count') : $this->count;
            return $items->paginate($this->count);
        }

        return $items->get();;
    }

    /**
     * @param $id
     * @return Category
     * @throws \Exception
     */
    public function findBy($column, $value)
    {
        $cacheyKey = $this->createCacheKey('findCategory:'.$column.':'.$value);

        if ($this->shouldCache()) {
            $cacheValue = $this->getCache($cacheyKey);

            if ($cacheValue) return $cacheValue;
        }

        $category = Category::where($column, $value)
            ->firstOrFail();

        if ($this->shouldCache()) {
            $this->putCache($cacheyKey, $category, ['category']);
        }

        return $category;
    }

    public function findCategoriesByGroup($group, $count)
    {
        $cacheyKey = $this->createCacheKey('findCategoriesByGroup:'.$group.':'.$count);

        if ($this->shouldCache()) {
            $cacheValue = $this->getCache($cacheyKey);

            if ($cacheValue) return $cacheValue;
        }

        $categories = Category::orderBy('name', 'asc')
            ->where('group', $group)
            ->paginate($count);

         if ($this->shouldCache()) {
             $this->putCache($cacheyKey, $categories, ['category']);
         }

        return $categories;
    }

    /**
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public function destroy($id)
    {
        $item = Category::findOrFail($id);
        $item->delete();
        return true;
    }
}