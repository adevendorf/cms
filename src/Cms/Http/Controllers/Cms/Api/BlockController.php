<?php
namespace Cms\Http\Controllers\Cms\Api;

use Cms\Models\Block;
use Cms\Repository\BlockRepository;
use Illuminate\Http\Request;
use Cms\Http\Controllers\Cms\Base\ApiController;
use CmsImage;

class BlockController extends ApiController
{
    /**
     * @var
     */
    protected $type;


    /**
     * BlockController constructor.
     * @param BlockRepository $repo
     */
    public function __construct(BlockRepository $repo)
    {
        $this->repo = $repo;
        $this->repo->setType('collection');
    }

    /**
     * @param Request $request
     * @return Block
     */
    public function store(Request $request)
    {
        $block = $this->repo->newModel();

        $block->fill($request->all());

        if ($request->input('type')) {
            $block->type = $request->input('type');
        }

        $block->save();

        return $block;
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $block = $this->repo->findById($id);

        $block->fill($request->all());

        $image = $request->input('image');

        if ($image) {
            $image = CmsImage::updateOrCreate($request, isset($image['id']) ? $image['id'] : false);
            $block->image_id = $image['id'];
        }

        $block->save();

        return $block;
    }
}
