<?php
namespace Cms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class CmsImage
 * @package Cms\Facades
 */
class CmsImage extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'CmsImageManager';
    }
}