<?php
namespace Cms\Http\Controllers\Cms\Api;

use Illuminate\Http\Request;
use Cms\Http\Controllers\Cms\Api\BlockController;
use Cms\Repository\BlockRepository;

class GalleryController extends BlockController
{
    /**
     * GalleryController constructor.
     * @param BlockRepository $repo
     */
    public function __construct(BlockRepository $repo)
    {
        $this->repo = $repo;
        $this->repo->setType('gallery');
    }
}
