<?php
namespace Cms\Models\Content;

use Cms\Models\Eloquent\Content as OrmModel;
use Cms\Traits\Render;
use Cms\Contracts\Renderable;
use CmsRepository;
use Cache;

class ContentImage extends OrmModel implements Renderable
{
    use Render;

    const CACHE_EXPIRE = 0.5;

    public function render($options = [])
    {
        if (!isset($options['image'])) {
            $imageId = $this->image_id;
            $image = Cache::get('image:'.$imageId, function () use ($imageId) {
                $value = CmsRepository::get('image')->findBy('id', $imageId);
                Cache::put('image:'.$imageId, $value, self::CACHE_EXPIRE);
                return $value;
            });
        } else {
            $image = $options['image'];
        }
        
        $cropName = 'default';

        if (isset($options['name'])) {
            $cropName = $options['name'];
        }

        $filename = '/img/cropped/' . $image->asset->path . $image->id . '/' . $cropName . '_' . $image->asset->original_filename;

        return view($this->getTemplate('image', 'default'), [
            'attributes' => $this->addStylingToDiv($this, $this->styling),
            'src' => $filename
        ]);
    }
}
