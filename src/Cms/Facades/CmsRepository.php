<?php
namespace Cms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class CmsRepository
 * @package Cms\Facades
 */
class CmsRepository extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'CmsRepositoryManager';
    }
}