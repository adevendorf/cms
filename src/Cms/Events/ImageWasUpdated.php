<?php
namespace Cms\Events;

use Cms\Models\Image;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class ImageWasUpdated extends Event
{
    use SerializesModels;

    public $image;

    public function __construct(Image $image)
    {
        $this->image = $image;
    }
}
