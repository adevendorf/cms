<?php
namespace Cms\Events;

use Cms\Models\CropSize;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class CropSizeWasUpdated extends Event
{
    use SerializesModels;

    public $cropSize;

    public function __construct(CropSize $cropSize)
    {
        $this->cropSize = $cropSize;
    }
}
