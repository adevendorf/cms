<?php
namespace Cms\Http\Controllers\Cms\Base;

use App\Http\Controllers\Controller;
use Faker\Factory;
use DB;

/**
 * Class CmsController
 * @package Cms\Http\Controllers
 */
class CmsController extends Controller
{
    
    /**
     * Enable DB Logging
     */
    public function enableDBLog()
    {
        DB::enableQueryLog();
    }


    /**
     * Ouput DB Logging
     */
    public function outputDBLog()
    {
        $log = DB::getQueryLog();
        dd($log);
    }

    /**
     * Create a UUID
     *
     * @return string
     */
    public function createUuid()
    {
        $faker = Factory::create();
        return $faker->uuid;
    }

}