<?php
namespace Cms\Http\Controllers\Cms\Api;

use Illuminate\Http\Request;
use Cms\Http\Controllers\Cms\Base\ApiController;
use Cms\Managers\TemplateManager;
use Cms\Repository\PageRepository;
use Cms\Generators\PageGenerator;
use Cms\Managers\ImageManager;
use Cache;

/**
 * Class PageController
 * @package Cms\Http\Controllers\Cms\Api
 */
class PageController extends ApiController
{

    protected $imageManager;

    /**
     * PageController constructor.
     * @param PageRepository $repo
     * @param ImageManager $imageManager
     */
    public function __construct(PageRepository $repo,  ImageManager $imageManager)
    {
        $this->repo = $repo;
        $this->imageManager = $imageManager;
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Cms\Traits\Exception
     */
    public function index(Request $request)
    {
        $pages = $this->repo->paginate($request);
        return $pages;
    }

    /**
     * @param Request $request
     * @return \Cms\Generators\Page
     */
    public function store(Request $request)
    {
        $generator = new PageGenerator();
        $page = $generator->createNewPage($request);

        return $page;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Cms\Traits\Exception
     */
    public function storeBlock(Request $request)
    {
        $page = $this->repo->findById($request->input('parent_id'));
        $block = $this->getRepository('block')->createFromPage($request);

        $page->blocks()->save($block);

        return $block;
    }


    /**
     * @param Request $id
     * @return mixed
     * @throws \Cms\Traits\Exception
     */
    public function show(Request $request, $id)
    {
        $templateManager = new TemplateManager();
        $page = $this->repo->findById($id);
        $page->templates = $templateManager->get();
        $page->sections = $this->getRepository('section')->get();
        return $page;
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $page = $this->repo->findById($id);
        $page->fill($request->all());


//        $existingSlug = $this->repo->findExistingSlug($id, $page->slug, $page->section_id);
//
//        if (count($existingSlug) > 0) {
//            $page->slug = $page->slug .'-'. count($existingSlug);
//        }

        $image = $request->input('image');
        if ($image) {
            $image = $this->imageManager->updateOrCreate($request, isset($image['id']) ? $image['id'] : false);
            $page->image_id = $image['id'];
        }
        $page->save();

        return $page;
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function publish($id)
    {
        $page = $this->repo->findById($id);
        $page->publish();
        return response()->json(null, 200);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function schedule(Request $request, $id)
    {
        $page = $this->repo->findById($id);

        $page->status = 'scheduled';
        $page->fill($request->all());
        $page->save();

        return response()->json(null, 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function hide($id)
    {
        $page = $this->repo->findById($id);
        $page->hide();
        
        return response()->json(null, 200);
    }

}
