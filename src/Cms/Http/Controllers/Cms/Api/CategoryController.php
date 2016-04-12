<?php
namespace Cms\Http\Controllers\Cms\Api;

use Cms\Models\Category;
use Illuminate\Http\Request;
use Cms\Http\Controllers\Cms\Base\ApiController;
use Cms\Repository\CategoryRepository;

/**
 * Class CategoryController
 * @package Cms\Http\Controllers\Cms\Api
 */
class CategoryController extends ApiController
{
    /**
     * CategoryController constructor.
     * @param CategoryRepository $repo
     */
    public function __construct(CategoryRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @param Request $request
     * @return Category
     */
    public function store(Request $request)
    {
        $category = $this->repo->newModel();
        $category->fill($request->all());
        $category->save();

        return $category;
    }

    /**
     * @param Request $request
     * @param $id
     * @return Category
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'slug' => 'required',
            'name' => 'required',
            'group' => 'required'
        ]);

        $category = $this->repo->findById($id);
        $category->update($request->all());

        return $category;
    }
}
