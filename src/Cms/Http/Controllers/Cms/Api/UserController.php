<?php
namespace Cms\Http\Controllers\Cms\Api;

use Cms\Repository\UserRepository;
use Illuminate\Http\Request;
use Cms\Http\Controllers\Cms\Base\ApiController;


/**
 * Class UserController
 * @package Cms\Http\Controllers\Cms\Api
 */
class UserController extends ApiController
{

    /**
     * UserController constructor.
     * @param UserRepository $repo
     * @param ImageManager $imageManager
     */
    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @param Request $request
     * @return \Cms\Models\User
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'name' => 'required'
        ]);

        $item = $this->repo->newModel();
        $item->fill($request->all());
        $item->save();
        return $item;
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'email' => 'required',
            'name' => 'required'
        ]);

        $user = $this->repo->findById($id);
        $user->fill($request->all());

        $image = $request->input('image');
        if ($image) {
            $image = $this->imageManager->updateOrCreate($request, isset($image['id']) ? $image['id'] : false);
            $user->image_id = $image['id'];
        }

        $user->save();

        return $this->show($request, $id);
    }

}
