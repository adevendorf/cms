<?php
namespace Cms\Generators;
use Cms\Models\Image;
use Cms\Models\ManagedCrop;
use Storage;
use Carbon\Carbon;
use Image as ImageTool;
use Event;
use Faker\Factory;

class ImageGenerator
{
    public function getFilename($image, $version)
    {
        $date = new Carbon($image->asset->created_at);

        return $date->year . '/' . $date->month . '/' . $date->day . '/' . $image->id . '_' . $version . '_' . str_replace(' ', '-', $image->asset->cms_filename);
    }

    public function getCropSettings($image, $version)
    {
        $crop = $image->crops->filter(function($item) use ($version) {
            return $item->name == $version;
        });

        if (count($crop) > 0) {
            return $crop->first();
        }

        $managedCrop = ManagedCrop::where('name', $version)->first();

        if (!$managedCrop) abort(404, 'Image Not Found');

        return $managedCrop;
    }

    public function getBasePath()
    {
        return 'img/';
    }

    public function createCropPreview($asset, $request)
    {
        $public = Storage::disk('public');
        $img = ImageTool::make($local->get($image->asset->original_filename));
    }

    public function createImage(Image $image, $version = 'default', $save = false, $useProtectedPath = false)
    {
        $local = Storage::disk('local');
        $public = Storage::disk('public');

        $crop = $this->getCropSettings($image, $version);
        $filename = $this->getFilename($image, $version);

        $img = ImageTool::make($local->get($image->asset->original_filename));

        $img = $this->crop($img, $image, $crop->name);

        if ($crop->max_dimension) {
            $img = $this->resizeImage($img, $crop->max_dimension);
        } else {
            $img = $this->resizeImage($img, config('cms.default_image_max_px'));
        }

        if ($save) {
            if ($useProtectedPath) {
                $public->put($this->getBasePath() . '_base/' . $filename, $img->encode());
            }
        }

        return $img->response();
    }


    public function createAssetThumbnail(Asset $asset)
    {
        try {
            $local = Storage::disk('local');
            $public = Storage::disk('public');

            $date = new Carbon($asset->created_at);

            $extension = explode('.', $asset->cms_filename);
            $extension = end($extension);

            $filename = 'img/_thumb/' . $date->year . '/' . $date->month . '/' . $date->day . '/' . $asset->preview_id . '.' . $extension;

            $img = Imager::make($local->get($asset->original_filename));

            $img = $this->limitPreviewSize($img);

            $public->put($filename, $img->encode());

        } catch(\Exception $e) {
            return false;
        }

        return true;
    }

    public function crop($img, $image, $version) {

        $values = $image->crops->filter(function($item) use($version) {
            return $item->name == $version;
        })->first();


        $imageWidth = $img->width();
        $imageHeight = $img->height();

        if (!$values) {
            // use predefined crop values from managed crop
            $ext = ManagedCrop::where('name', $version)->first();

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

    public function limitPreviewSize($img)
    {
        if ($img->height() > $img->width()) {
            $img->resize(null, 640, function ($constraint) {
                $constraint->aspectRatio();
            });
        } else {
            $img->resize(640, null, function ($constraint) {
                $constraint->aspectRatio();;
            });
        }

        return $img;
    }

    public function generateThumbs(Request $request)
    {
        $this->paginate = 5;
        $assets = $this->paginate($request);

        foreach ($assets as $asset) {
            $this->createThumbFromAsset($asset);
        }
        $assetArray = $assets->toArray();

        if ( $assetArray['current_page'] ==  $assetArray['last_page']) {
            return ['success' => true];
        }
        return redirect($assetArray['next_page_url']);

    }

    public function createBaseImage($image)
    {
        $this->createAssetThumbnail($image, 'default', true, true);
    }

}