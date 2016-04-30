<?php
namespace Cms\Http\Controllers\Cms\Api;

use Illuminate\Http\Request;
use Cms\Repository\ContentRepository;
use Cms\Http\Controllers\Cms\Base\ApiController;
use CmsImage;

class ContentController extends ApiController
{
    public function __construct(ContentRepository $repo)
    {
        $this->repo = $repo;
    }

    public function store(Request $request)
    {
        $content = $this->repo->newModel();
        $content->fill($request->all());
        $content->save();

        return $this->show($request, $content->id);
    }

    public function update(Request $request, $id)
    {
        $content = $this->repo->findById($id);
        $content->fill($request->all());

        $image = $request->input('image');

        if ($image) {
            $image = CmsImage::updateOrCreate($request, isset($image['id']) ? $image['id'] : false);
            $content->image_id = $image['id'];
        }
        $content->save();

        return $this->show($request, $content->id);
    }

    public function reorder(Request $request)
    {
        $json = $request->input('items');

        foreach ($json as $content) {
            $this->repo->updateOrder(intval($content['id']), intval($content['order']));
        }
        return $this->returnSuccess();
    }
}
