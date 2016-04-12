<?php
namespace Cms\Http\Controllers\Cms\Api;

use Cms\Models\CropSize;
use Cms\Repository\CropSizeRepository;
use Illuminate\Http\Request;
use Cms\Http\Controllers\Cms\Base\ApiController;
use Auth;
use Event;
use Cms\Events\CropSizeWasUpdated;

class CropSizeController extends ApiController
{
    protected $repo;

    public function __construct(CropSizeRepository $repo)
    {
        $this->repo = $repo;
    }

    public function store(Request $request)
    {
        $item = $this->repo->newModel();
        $item->fill($request->all());
        $item->save();
        return $item;
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'max_dimension' => 'required',
            'aspect_ratio' => 'required'
        ]);

        $item = $this->repo->findById($id);
        $item->update($request->all());

        Event::fire(new CropSizeWasUpdated($item));

        return $item;
    }

    public function destroy(Request $request, $id)
    {
        $item = $this->repo->findById($id);
        $this->getRepository('crop')->destroyByType($item->name);
        $item->delete();
        return response()->json(null, 200);
    }
}
