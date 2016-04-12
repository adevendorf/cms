<?php
namespace Cms\Http\Controllers\Cms\Api;

use Cms\Models\Section;
use Illuminate\Http\Request;
use Cms\Http\Controllers\Cms\Base\ApiController;
use Cms\Managers\ImageManager;
use Cms\Repository\SectionRepository;
use Auth;
use Illuminate\Support\Collection;

/**
 * Class SectionController
 * @package Cms\Http\Controllers
 */
class SectionController extends ApiController
{
    /**
     * SectionController constructor.
     * @param SectionRepository $repo
     */
    public function __construct(SectionRepository $repo)
    {
        $this->repo = $repo;
    }


    /**
     * @param Request $request
     * @return Section
     */
    public function store(Request $request)
    {
        $section = $this->repo->newModel();
        $section->fill($request->all());
        $section->save();

        return $section;
    }


    /**
     * @param Request $request
     * @param $id
     * @return Section
     * @throws \Exception
     */
    public function update(Request $request, $id)
    {
        $section = $this->repo->findById($id);
        $section->fill($request->all());

        $image = $request->input('image');

        if ($image) {
            $image = $this->imageManager->updateOrCreate($request, isset($image['id']) ? $image['id'] : false);
            $section->image_id = $image['id'];
        }
        $section->save();

        return $section;
    }
}
