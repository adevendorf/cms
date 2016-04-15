<?php
namespace Cms\Render;

use Cms\Render\BaseRender;
use Carbon\Carbon;
use Exception;

class ImageRender extends BaseRender
{
    public function render($content, $cropname = 'default')
    {
        try {
            $filename = '/img/cropped/' . $content->image->asset->path . $content->image->id . '/' . $cropname . '_' . $content->image->asset->original_filename;

            return view($this->template('image', $content->template), [
                'attributes' => $this->addStylingToDiv($content, $content->styling),
                'src' => $filename
            ]);
        } catch(Exception $e) {
            return '';
        }
    }

}