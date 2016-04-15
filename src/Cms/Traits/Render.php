<?php
namespace Cms\Traits;

use Cms\Traits\Meta;
use Storage;

trait Render
{
    use Meta;

    public function multiArrayToArrayOfObjects($array)
    {
        $newArray  = [];
        foreach($array as $item) {
            $newArray[] = $this->toObject($item);
        }
        return $newArray;
    }

    public function toObject($array)
    {
        $object = new stdClass();

        foreach ($array as $key => $value)
        {
            $object->$key = $value;
        }

        return $object;
    }

    public function template($type, $name)
    {
        $local = Storage::disk('local_views');

        if ($name == '_default') $name = 'default';

        if ($local->has('cms/themes/'. config('cms.theme') .'/content/'. $type .'-'. $name .'.blade.php')) {
            return 'cms/themes/'. config('cms.theme') .'/content/'. $type .'-'. $name;
        } elseif ($local->has('cms/themes/default/content/'. $type .'-'. $name .'.blade.php')) {
            return 'cms/themes/default/content/'. $type .'-'. $name;
        } elseif ($local->has('cms/themes/'. config('cms.theme') .'/content/'. $type .'-default.blade.php')) {
            return 'cms/themes/'. config('cms.theme') .'/content/'. $type .'-default';
        } elseif ($local->has('cms/themes/default/content/'. $type .'-default.blade.php')) {
            return 'cms/themes/default/content/' . $type . '-default';
        } else {
            return abort('404', 'Template Not Found');
        }
    }


    public function addStylingToDiv($content, $options)
    {
        if (!$options || $options == '') return ' class="content content-' . strtolower($content->type) . '"';

        $options = json_decode($options);

        $html = '';

        if (isset($options->float)) {
            $html .= ' style="float:' . $options->float . ';"';
        }

        if (isset($options->cssId)) {
            $html .= ' id="' . $options->cssId . '"';
        }

        $html .= ' class="content content-' . strtolower($content->type);
        if (isset($options->cssClass)) {
            $html .= ' ' . $options->cssClass . '"';
        } else {
            $html .= '"';
        }

        return $html;
    }

}