<?php
namespace Cms\Listeners;

use Cms\Events\CropSizeWasUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Storage;
use Log;
use Cms\Models\Crop;
use Cms\Models\Image;

class CropSizeUpdateListener
{

    /**
     * Handle the event.
     *
     * @param  CropSizeWasUpdated  $event
     * @return void
     */
    public function handle(CropSizeWasUpdated $event)
    {
        $disk = Storage::disk('public');
        $cropSizeName = $event->cropSize->name;

        Log::info('CropSize Updated: ' . $cropSizeName);

        $items = Image::with('asset')->get();

        foreach($items as $item) {
            $path = $item->asset->path;
            $imagePath = strtolower('/img/cropped/'.$path.$item->id.'/'.$cropSizeName.'_'.$item->asset->original_filename);
            if ($disk->exists($imagePath)) {
                $disk->delete($imagePath);
                Log::info('Deleted file: ' . $imagePath);
            }
        }
    }
}