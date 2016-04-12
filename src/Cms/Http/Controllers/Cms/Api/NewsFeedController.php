<?php
namespace Cms\Http\Controllers\Cms\Api;

use Cms\Repository\NewsFeedRepository;
use Illuminate\Http\Request;
use Cms\Http\Controllers\Cms\Base\ApiController;

class NewsFeedController extends ApiController
{

    public function __construct(NewsFeedRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index(Request $request) {
        return $this->getRepository('section')->paginate($request);
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
            'feed' => $this->getRepository('section')->findBy('id', $id),
            'items' => $this->repo->findBySection($request, $id)
            ];
    }

    public function destroy(Request $request, $id) {
        $item = $this->model->where('site_id', $this->site->id)->findOrFail($id);
        $item->delete();
        return response()->json(null, 200);
    }
}
