<?php
namespace Cms\Http\Controllers\Cms;

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

    public function getImagePreview(Request $request, $id)
    {
        $image = $this->repo->findById($id);
        $crop = $image->crops->filter(function($item) {
            return $item->name == 'default';
        })->first();

        return $image->asset->createCropPreview('small', $crop->crop_x, $crop->crop_y, $crop->crop_width, $crop->crop_height);
    }

    public function getCropPreview(Request $request, $id)
    {
        $asset = $this->getRepository('asset')->findById($id);
        return $asset->createCropPreview('small', $request->input('crop_x'), $request->input('crop_y'), $request->input('crop_width'), $request->input('crop_height'));
    }

    public function getLargeCropPreview(Request $request, $id)
    {
        $asset = $this->getRepository('asset')->findById($id);
        return $asset->createCropPreview('large', $request->input('crop_x'), $request->input('crop_y'), $request->input('crop_width'), $request->input('crop_height'));
    }

    public function renderPublicCropExtended(Request $request, $siteFolder, $year, $month, $day, $dir1, $id, $version, $filename)
    {
        return $this->render($siteFolder, $year, $month, $day, $dir1, $id,$version, $filename);
    }

    public function render($siteFolder, $year, $month, $day, $dir1, $id, $version, $filename)
    {
        $image = Image::with('crops', 'asset')->find($id);

        $crop = $image->crops->filter(function($item) use ($version) {
            return $item->name == $version;
        });

        if (count($crop) > 0) {
            $crop = $crop->first();
        } else {
            $managedCrop = CropSize::where('name', $version)->first();
            if (!$managedCrop) abort(404, 'Image Not Found');
            $crop = Crop::create([
                'name' => $managedCrop->name,
                'image_id' => $image->id,
                'max_dimension' => $managedCrop->max_dimension,
                'aspect_ratio' => $managedCrop->aspect_ratio
            ]);
        }

        $local = Storage::disk('local');
        $img = ImageTool::make($local->get('asset/' . $image->asset->path . $image->asset->cms_filename));

        $img = $this->crop($img, $image, $crop->name);

        if ($crop->max_dimension) {
            $img = $this->resizeImage($img, $crop->max_dimension);
        }

        $this->fileManager->saveFile('img/cropped/'.$siteFolder.'/'.$year.'/'.$month.'/'.$day.'/'.$dir1.'/'.$id.'/'.$version.'_'.$filename, $img->encode());

        return $img->response();
    }

    public function resizeImage($img, $max)
    {

        if ($img->height() > $img->width()) {
            $img->resize(null, $max, function ($constraint) {
                $constraint->aspectRatio();
//                $constraint->upsize();
            });
        } else {
            $img->resize($max, null, function ($constraint) {
                $constraint->aspectRatio();
//                $constraint->upsize();
            });
        }

        return $img;
    }

    public function crop($img, $image, $version = 'default') {

        $values = $image->crops->filter(function($item) use($version) {
            return $item->name == $version;
        })->first();

        $imageWidth = $img->width();
        $imageHeight = $img->height();

        if (!$values) {
            // use predefined crop values from managed crop
            $ext = CropSize::where('name', $version)->first();

            $ar = explode(':', $ext->aspect_ratio);
            $ratio = $ar[1] / $ar[0];

            $autoHeight = intval(round($imageWidth * $ratio));
            $autoWidth = intval(round($autoHeight * $ratio));

            $width = $imageWidth;
            $height = $autoHeight;

            $img->fit(intval($width), intval($height), function($constraint) {}, $image->asset->crop_preference);

        } else {
            //use user defined crop values
            $x = $imageWidth * ($values->crop_x/100);
            $y = $imageHeight * ($values->crop_y/100);

            $width = $imageWidth * ($values->crop_width/100);
            $height = $imageHeight * ($values->crop_height/100);

            $img->crop(intval($width), intval($height), intval($x), intval($y));
        }

        return $img;
    }

}
