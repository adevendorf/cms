<?php
namespace Cms\Models\Content;

use Cms\Models\Eloquent\Content as OrmModel;
use Cms\Traits\Render;
use Cms\Contracts\Renderable;
use CmsRepository;

class ContentImage extends OrmModel implements Renderable
{
    use Render;

    public function render()
    {
        $image = CmsRepository::get('image')->findBy('id', $this->image_id);
        
        $cropName = 'default';
        $filename = '/img/cropped/' . $image->asset->path . $image->id . '/' . $cropName . '_' . $image->asset->original_filename;

        return view($this->getTemplate('image', $this->template), [
            'attributes' => $this->addStylingToDiv($this, $this->styling),
            'src' => $filename
        ]);
    }
}
