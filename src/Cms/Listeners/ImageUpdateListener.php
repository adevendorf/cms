<?php
namespace Cms\Listeners;

use Cms\Events\ImageWasUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Storage;
use Log;

class ImageUpdateListener implements ShouldQueue
{

    /**
     * Handle the event.
     *
     * @param  ImageWasUpdated  $event
     * @return void
     */
    public function handle(ImageWasUpdated $event)
    {
        $image = $event->image;
        $asset = $image->asset;

        $path = $asset->path;
        $disk = Storage::disk('public');

        $imageDir = '/img/cropped/'.$path.$image->id;

        if ($disk->exists($imageDir)) {
            $disk->deleteDirectory($imageDir);
            Log::info('Deleted Directory: ' . $imageDir);
        }
    }
}