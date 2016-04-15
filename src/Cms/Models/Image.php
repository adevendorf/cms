<?php
namespace Cms\Models;

use Cms\Models\Eloquent\Content as OrmModel;
use Cms\Traits\CmsRepo;
use Cms\Traits\Render;
use Cms\Contracts\Renderable;

/**
 * Class Image
 * @package Cms\Models
 */
class Image extends OrmModel implements Renderable
{
    use CmsRepo;
    use Render;

    public function render()
    {
        $image = $this->getRepository('image')->findBy('id', $this->image_id);
        
        $cropName = 'default';
        $filename = '/img/cropped/' . $image->asset->path . $image->id . '/' . $cropName . '_' . $image->asset->original_filename;

        return view($this->template('image', $this->template), [
            'attributes' => $this->addStylingToDiv($this, $this->styling),
            'src' => $filename
        ]);
    }
}
