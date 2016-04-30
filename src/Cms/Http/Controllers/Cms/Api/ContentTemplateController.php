<?php
namespace Cms\Http\Controllers\Cms\Api;

use Illuminate\Http\Request;
use Cms\Http\Controllers\Cms\Base\ApiController;
use Cms\Models\Extension;

class ContentTemplateController extends ApiController
{
    public function showByKey(Request $request, $key)
    {
        $items = Extension::where('type', 'template')->where('key', $key)->get();
        return $items;
    }
}