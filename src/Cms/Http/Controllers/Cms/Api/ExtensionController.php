<?php
namespace Cms\Http\Controllers\Cms\Api;

use Illuminate\Http\Request;
use Cms\Http\Controllers\Cms\Base\ApiController;
use Cms\Repository\ExtensionRepository;
use Cms\Models\Extension;

class ExtensionController extends ApiController
{

    /**
     * ExtensionController constructor.
     * @param ExtensionRepository $repo
     */
    public function __construct(ExtensionRepository $repo)
    {
        $this->repo = $repo;
    }


    /**
     * @param Request $request
     * @return Extension
     */
    public function store(Request $request)
    {
        $extension = $this->repo->newModel();
        $extension->fill($request->all());
        $extension->save();

        return $extension;
    }

    /**
     * @param $id
     * @return Extension
     * @throws \Exception
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'type' => 'required',
            'key' => 'required',
            'value' => 'required'
        ]);

        $extension = $this->repo->findById($id);
        $extension->update($request->all());

        return $extension;
    }
}
