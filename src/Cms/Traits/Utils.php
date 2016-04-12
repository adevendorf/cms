<?php
namespace Cms\Traits;

use Illuminate\Http\Request;

trait Utils
{
    public function convertToSelectArray($array)
    {
        $new = [];

        foreach ($array as $item)
        {
            $new[] = [
                'text' => $this->convertTextForDisplay($item),
                'value' => $item
            ];
        }

        return $new;
    }

    public function convertTextForDisplay($text)
    {
        $text = str_replace('cms', 'CMS', $text);
        $text = str_replace('_', ' ', $text);
        return ucwords($text);
    }

    public function clean($input) {
        $input = str_replace(' ', '-', $input);
        return preg_replace('/[^A-Za-z0-9\-]/', '', $input);
    }

    public function cleanToLower($input) {
        return strtolower($this->clean($input));
    }
}