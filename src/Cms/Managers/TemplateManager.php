<?php
namespace Cms\Managers;

use Storage;

class TemplateManager
{
    public function get()
    {
        $templates = array_map(function($item) {
            $indexSlash = strrpos($item, "/") + 1;
            return str_replace(".blade.php", "", substr($item, $indexSlash));

        }, Storage::disk('local_views')->files('/cms/themes/' . config('cms.theme') . '/page'));

        return $templates;
    }

    public function findOrDefault($theme, $name)
    {
        return $templates;
    }
}