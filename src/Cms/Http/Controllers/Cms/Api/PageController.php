<?php
namespace Cms\Http\Controllers\Cms\Api;

use Illuminate\Http\Request;
use Cms\Http\Controllers\Cms\Base\ApiController;
use Cms\Managers\TemplateManager;
use Cms\Managers\PageManager;
use Cms\Repository\PageRepository;
use CmsRepository;
use CmsImage;

class PageController extends ApiController
{
    /**
     * PageController constructor.
     * @param PageRepository $repo
     */
    public function __construct(PageRepository $repo)
    {
        $this->repo = $repo;
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
        $pageManager = new PageManager();
        $page = $pageMananger->createNewPage($request);

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
        $block = CmsRepository::get('block')->createFromPage($request);

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
        $page = $this->repo->findById($id);

        $templateManager = new TemplateManager();
        $page->templates = $templateManager->get();

        $page->sections = CmsRepository::get('section')->get();

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

        $image = $request->input('image');

        if ($image) {
            $image = CmsImage::updateOrCreate($request, isset($image['id']) ? $image['id'] : false);
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

        return $this->returnSuccess();
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

        return $this->returnSuccess();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function hide($id)
    {
        $page = $this->repo->findById($id);
        $page->hide();
        
        return $this->returnSuccess();
    }

}
