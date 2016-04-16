<?php
namespace Cms\Http\Controllers\Cms\Api;

use Cms\Repository\NewsFeedRepository;
use Illuminate\Http\Request;
use Cms\Http\Controllers\Cms\Base\ApiController;
use CmsRepository;

class NewsFeedController extends ApiController
{

    public function __construct(NewsFeedRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index(Request $request) {
        return CmsRepository::get('section')->paginate($request);
    }

    public function store(Request $request) {
        $newsFeed = $this->repo->newModel();
        $newsFeed->fill($request->all());
        $newsFeed->order = 0;
        $newsFeed->save();

        $newsFeed->page;

        return $newsFeed;
    }

    public function show(Request $request, $id)
    {
        return [
            'feed' => CmsRepository::get('section')->findBy('id', $id),
            'items' => $this->repo->findBySection($request, $id)
            ];
    }

    public function destroy(Request $request, $id) {
        $item = $this->repo->findOrFail($id);
        $item->delete();
        return $this->returnSuccess();
    }
}
