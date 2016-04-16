<?php
namespace Cms\Events;

use Cms\Models\Image;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;

/**
 * Class ImageWasUpdated
 * @package Cms\Events
 */
class ImageWasUpdated extends Event
{
    use SerializesModels;

    /**
     * @var Image
     */
    public $image;

    /**
     * ImageWasUpdated constructor.
     * @param Image $image
     */
    public function __construct(Image $image)
    {
        $this->image = $image;
    }
}
