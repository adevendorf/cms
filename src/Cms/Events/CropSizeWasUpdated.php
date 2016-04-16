<?php
namespace Cms\Events;

use Cms\Models\CropSize;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;

/**
 * Class CropSizeWasUpdated
 * @package Cms\Events
 */
class CropSizeWasUpdated extends Event
{
    use SerializesModels;

    /**
     * @var CropSize
     */
    public $cropSize;

    /**
     * CropSizeWasUpdated constructor.
     * @param CropSize $cropSize
     */
    public function __construct(CropSize $cropSize)
    {
        $this->cropSize = $cropSize;
    }
}
