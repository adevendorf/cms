<?php
namespace Cms\Http\Controllers\Cms\Api;

use Cms\Repository\AssetRepository;
use Illuminate\Http\Request;
use Cms\Http\Controllers\Cms\Base\ApiController;
use Cms\Managers\FileManager;


/**
 * Class AssetController
 * @package Cms\Http\Controllers\Cms\Api
 */
class AssetController extends ApiController
{

    /**
     * AssetController constructor.
     * @param AssetRepository $repo
     * @param FileManager $fileManager
     */
    public function __construct(AssetRepository $repo, FileManager $fileManager)
    {
        $this->repo = $repo;
        $this->fileManager = $fileManager;
    }


    /**
     * @param Request $request
     * @return \Cms\Models\Asset
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'type' => 'required',
            'file' => 'required'
        ]);

        $file = $this->fileManager->upload($request);

        if (!$file) {
            abort(500, 'Upload Error');
        }

        $asset = $this->repo->newModel();

        $asset->original_filename = $file['original_filename'];
        $asset->cms_filename = $file['cms_filename'];
        $asset->max_dimension = $file['max_dimension'];
        $asset->path = $file['path'];
        $asset->keywords = $request->input('keywords');
        $asset->type = $request->input('type');
        $asset->save();

        $asset->createImages();

        return $asset;
    }


    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'type' => 'required'
        ]);

        $asset = $this->repo->findById($id);
        $asset->update($request->all());

        return $asset;
    }

    /**
     * @param Request $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $local = Storage::disk('local');
        $asset = $this->repo->findById($id);

        try {
            $local->delete($asset->original_filename);
        } catch(\Exception $e) {
            throw new $e->getMessage();
        }

        $this->repo->destroy($id);

        return response()->json(null, 200);
    }
}
