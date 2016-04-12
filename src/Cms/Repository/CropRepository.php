<?php
namespace Cms\Repository;

use Cms\Repository\Base\Repository;
use Cms\Models\Crop;

class CropRepository extends Repository
{
    public function destroyByType($name)
    {
        $crops = Crop::where('name', $name)->delete();
        return true;
    }
}