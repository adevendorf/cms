<?php
namespace Cms\Models;

use Cms\Models\Eloquent\Asset as OrmModel;
use Storage;
use Carbon\Carbon;
use Image as ImageTool;

class Asset extends OrmModel  {

    public function createImages()
    {
        $local = Storage::disk('local');
        $public = Storage::disk('public');

        $img = ImageTool::make($local->get('asset/'.$this->path.$this->cms_filename));
        $img = $this->limitPreviewSize($img, 1000);

        $public->put($this->getUtilityPath(), $img->encode());

        $img = $this->limitPreviewSize($img, 150);

        $public->put($this->getPreviewPath(), $img->encode());
    }


    public function getBasePath()
    {
        return 'img/';
    }

    public function getExtension()
    {
        $filename = explode('.', $this->cms_filename);
        return end($filename);
    }

    public function getUtilityPath()
    {
        return $this->getBasePath() . 'asset/utility/' . $this->path . $this->cms_filename;
    }

    public function getPreviewPath()
    {
        return $this->getBasePath() . 'asset/preview/' . $this->path . $this->cms_filename;
    }

    public function limitPreviewSize($img, $px = 640)
    {
        if ($img->height() > $img->width()) {
            $img->resize(null, $px, function ($constraint) {
                $constraint->aspectRatio();
            });
        } else {
            $img->resize($px, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        return $img;
    }

    public function createCropPreview($sourceSize, $x, $y, $w, $h)
    {
        $img = ImageTool::make($this->getCropSource($sourceSize));
        $img = $this->crop($img, $x, $y, $w, $h);

        return $img->response();
    }

    public function getCropSource($sourceSize = 'small')
    {
        $path = $sourceSize == 'small' ?  $this->getPreviewPath() : $this->getUtilityPath();
        $public = Storage::disk('public');

        return $public->get($path);
    }

    public function crop($img, $cX, $cY, $cW, $cH)
    {

        $imageWidth = $img->width();
        $imageHeight = $img->height();

        //use user defined crop values
        $x = $imageWidth * ($cX/100);
        $y = $imageHeight * ($cY/100);

        $width = $imageWidth * ($cW/100);
        $height = $imageHeight * ($cH/100);

        $img->crop(intval($width), intval($height), intval($x), intval($y));

        return $img;
    }
}
