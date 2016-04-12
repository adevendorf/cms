<?php
namespace Cms\Http\Controllers\Cms\Api;

use Cms\Generators\ImageGenerator;
use Cms\Http\Controllers\Cms\Base\ApiController;
use Cms\Managers\FileManager;
use Cms\Models\Extension;
use Cms\Repository\ImageRepository;
use Illuminate\Http\Request;
use File;
use Route;
use Storage;
use Event;
use Image as ImageTool;
use Carbon\Carbon;
use Cms\Models\Image;
use Cms\Models\Asset;
use Cms\Models\Crop;
use Cms\Models\CropSize;

use App\Events\ImageWasUpdated;

/**
 * Class ImageController
 * @package Cms\Core\Image
 */
class ImageController extends ApiController
{
    protected $repo;
    protected $fileManager;

    public function __construct(ImageRepository $repo, FileManager $fileManager)
    {
        $this->generator = new ImageGenerator();
        $this->repo = $repo;
        $this->fileManager = $fileManager;
    }

    public function renderPublic(Request $request, $year, $month, $day, $id, $version, $filename)
    {

        $image = $this->repo->findById($id);
        return $this->generator->createImage($image, $version);
    }


    public function destroy(Request $request, $id)
    {

        $image = $this->repo->findById($id);
        foreach($image->crops as $crop) {
            $crop->delete();
        }
        $image->delete();

        return response(['status' => 200]);
    }

    public function update(Request $request, $id)
    {
        $image = Image::find($id);
        $image->update($request->all());

        $image->asset;
        $image->crops;


        return $image;
    }

    public function store(Request $request)
    {
        $image = new Image;
        $image->asset_id = $request->input('asset.id');
        $image->save();

        $crop = new Crop;
        $crop->image_id = $image->id;
        $crop->name = 'default';
        $crop->save();

        $this->generator->createBaseImage($image);

        $image->crop = $crop;

        return $image;
    }
}
