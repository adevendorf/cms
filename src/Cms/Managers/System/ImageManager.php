<?php
namespace Cms\Managers\System;

use Cms\Models\Image;
use Cms\Models\Crop;
use Cms\Models\Asset;
use DB;
use Config;
use Auth;
use Storage;
use Event;
use Cms\Events\ImageWasUpdated;

class ImageManager
{
    public static function create($request)
    {
        $asset = Asset::find($request->input('image.asset.id'));

        $image = new Image();
        $image->fill($request->input('image'));
        $image->created_by =Auth::user()->id;
        $image->asset_id = $request->input('image.asset.id');
        $image->save();

        $crop = new Crop();
        $crop->image_id = $image->id;
        $crop->created_by =Auth::user()->id;
        $crop->max_dimension = $asset->max_dimension;
        $crop->save();

        return $image;
    }

    public static function update($request)
    {
        $image = Image::findOrFail($request->input('image.id'));
        $image->fill($request->input('image'));
        $image->asset_id = $request->input('image.asset.id');
        $image->save();

        self::updateCrops($image, $request->input('image.crops'));

        Event::fire(new ImageWasUpdated($image));

        return $image;
    }


    public static function updateOrCreate($request, $imageId)
    {
        if (!$imageId) {
            return self::create($request);
        }
        return self::update($request);
    }

    public static function updateCrops($item, $crops)
    {
        DB::transaction(function () use ($item, $crops) {

            Crop::where('image_id', $item->id)->delete();

            foreach ($crops as $cropData) {
                $crop = new Crop($cropData);
                $crop->image_id = $item->id;
                $crop->created_by = Auth::user()->id;
                $crop->save();
            }
        });
    }

    public static function generateUrls($image, $crop)
    {
       return '/img/cropped/' . $image->asset->path . $image->id . '_' . strtolower($crop) . '_' . $image->asset->original_filename;
    }
}