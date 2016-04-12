<?php


/**
 * Class ImageController
 * @package Cms\Core\Image
 */
class OldImageController
{
    protected $repo;

    public function __construct(ImageRepository $repo)
    {
        $this->generator = new ImageGenerator();
        $this->repo = $repo;
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

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $image = Image::find($id);

        $image->update($request->all());
//        $image->asset_id = $request->input('asset_id');
//        $image->order = $request->input('order');
//        $image->size = $request->input('size');
//        $image->crop = $request->input('crop');
//        $image->caption = $request->input('caption');
//        $image->save();

        $image->asset;
        $image->crops;

        Event::fire(new ImageWasUpdated($image));

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

//        $this->createPreviewFromImage($image);
//        $this->createRealImageFromImage($image, $crop);

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
//        dd($crop);
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

//    public function generateImages(Request $request)
//    {
//
//        $images = Image::with('asset', 'crops')->paginate(5);
//
//
//        foreach ($images as $image) {
//            foreach($image->crops as $crop) {
//                //$this->createPreviewFromImage($image, $crop);
//                $this->createRealImageFromImage($image, $crop);
//            }
//        }
//        $imageArray = $images->toArray();
//
//        if ( $imageArray['current_page'] ==  $imageArray['last_page']) {
//            return ['success' => true];
//        }
//        return redirect($imageArray['next_page_url']);
//    }


//    public function createRealImageFromImage($image, $crop)
//    {
//        $asset = Asset::find($image->asset_id);
//
//        $date = new Carbon($asset->created_at);
//        $filename = 'img/r/' . $date->year . '/' . $date->month . '/' . $date->day . '/' . $image->id . '_' . $crop->name . '_' . str_replace(' ', '-', $asset->cms_filename);
//
//        $local = Storage::disk('local');
//        $public = Storage::disk('public');
//
//        $img = ImageTool::make($local->get($asset->original_filename));
//
//        $img = $this->crop($img, $image, $crop->name);
//
//        if ($crop->max_dimension) {
//            $img = $this->resizeImage($img, $crop->max_dimension);
//        }
//
//        $public->put($filename, $img->encode());
//    }





//    public function createPreviewFromImage($image, $crop)
//    {
//        $asset = Asset::find($image->asset_id);
//
//        $extension = explode('.', $asset->cms_filename);
//        $extension = end($extension);
//        $filename = 'img/_preview/' . $image->id . '.' . $extension;
//
//        $local = Storage::disk('local');
//        $public = Storage::disk('public');
//
//        $img = ImageTool::make($local->get($asset->original_filename));
//
//        $img = $this->crop($img, $image, $crop->name);
//
//
//        $img = $this->limitPreviewSize($img);
//
//        $public->put($filename, $img->encode());
//    }



//    public function realtimeAsset(Request $request, $id)
//    {
//        $asset = Asset::find($id);
//
//        $local = Storage::disk('local');
//
//        $img = ImageTool::make($local->get($asset->original_filename));
//
//        $img = $this->limitPreviewSize($img);
//
//        return $img->response();
//    }


//    public function realtimeImage(Request $request, $id)
//    {
//        $imageType = '\\Cms\\Models\\' . ucfirst($request->input('type')) . 'Image';
//        $image = $imageType::find($id);
//        $image->asset;
//        $image->crops;
//
//        $local = Storage::disk('local');
//
//        $img = ImageTool::make($local->get($image->asset->original_filename));
//
//        $cropName = $request->input('crop');
//
//        if ($cropName) {
//            if (count($image->crops) > 0) {
//                if (count($image->crops->filter(function($crop) use($cropName) {
//                        return $crop->name == $cropName;
//                    })) > 0) {
//                    $img = $this->crop($img, $image, $cropName);
//                }
//            }
//        }
//
//        $img = $this->limitPreviewSize($img);
//
//        return $img->response();
//    }



//    public function realtimeImageByAsset(Request $request, $id)
//    {
//        $asset = Asset::find($id);
//
//        $local = Storage::disk('local');
//
//        $img = ImageTool::make($local->get($asset->original_filename));
//
//        $imageWidth = $img->width();
//        $imageHeight = $img->height();
//
//
//        if ($request->input('crop')) {
//            $x = $imageWidth * ($request->input('crop_x') / 100);
//            $y = $imageHeight * ($request->input('crop_y') / 100);
//            $width = $imageWidth * ($request->input('crop_width') / 100);
//            $height = $imageHeight * ($request->input('crop_height') / 100);
//
//            $img = $img->crop(intval($width), intval($height), intval($x), intval($y));
//        }
//
//        $img = $this->limitPreviewSize($img);
//
//        return $img->response();
//    }



//    public function createNewImageFromCrop($model)
//    {
//        $image = Image::find($model->image_id)->first();
//        $this->createRealImageFromImage($image, $model);
//    }


    /*
     *
     *
     *
     *
     *
     *
     */
//    public function realImageFromFilename(Request $request, $year, $month, $day, $id, $version, $filename)
//    {
//        $image = Image::with('crops', 'asset')->find($id);
//
//        $crop = $image->crops->filter(function($item) use ($version) {
//            return $item->name == $version;
//        });
//
//        if (count($crop) > 0) {
//            $crop = $crop->first();
//        } else {
//            $managedCrop = ManagedCrop::where('name', $version)->first();
//            if (!$managedCrop) abort(404, 'Image Not Found');
//            $crop = $managedCrop;
//        }
//
//        $date = new Carbon($image->asset->created_at);
//        $filename = 'img/r/' . $date->year . '/' . $date->month . '/' . $date->day . '/' . $image->id . '_' . $version . '_' . str_replace(' ', '-', $image->asset->cms_filename);
//
//        $local = Storage::disk('local');
//        $public = Storage::disk('public');
//
//        $img = ImageTool::make($local->get($image->asset->original_filename));
//
//        $img = $this->crop($img, $image, $crop->name);
//
//        if ($crop->max_dimension) {
//            $img = $this->resizeImage($img, $crop->max_dimension);
//        }
//
//        return $img->response();
//    }

    public function renderPublicCrop(Request $request, $dir1, $dir2, $dir3, $id, $version, $filename)
    {
        $image = Image::with('crops', 'asset')->find($id);

        $crop = $image->crops->filter(function($item) use ($version) {
            return $item->name == $version;
        });

        if (count($crop) > 0) {
            $crop = $crop->first();
        } else {
            $managedCrop = ManagedCrop::where('name', $version)->first();
            if (!$managedCrop) abort(404, 'Image Not Found');
            $crop = $managedCrop;
        }

        $filename = 'img/cropped/' . $image->asset->path . $image->id . '_' . $version . '_' . str_replace(' ', '-', $image->asset->cms_filename);

        $local = Storage::disk('local');
        $public = Storage::disk('public');

        $img = ImageTool::make($local->get('asset/' . $image->asset->path . $image->asset->cms_filename));

        $img = $this->crop($img, $image, $crop->name);

        if ($crop->max_dimension) {
            $img = $this->resizeImage($img, $crop->max_dimension);
        }

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


//    public function limitPreviewSize($img)
//    {
//        if ($img->height() > $img->width()) {
//            $img->resize(null, 640, function ($constraint) {
//                $constraint->aspectRatio();
////                $constraint->upsize();
//            });
//        } else {
//            $img->resize(640, null, function ($constraint) {
//                $constraint->aspectRatio();
////                $constraint->upsize();
//            });
//        }
//
//        return $img;
//    }


    public function crop($img, $image, $version = 'default') {

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

}
