<?php
namespace Cms\Providers;

use Cms\Models\Form;
use Illuminate\Support\ServiceProvider;
use DB;
use App;
use Blade;
//use HTML;

/**
 * Class CmsServiceProvider
 * @package Cms\Providers
 */
class CmsServiceProvider extends ServiceProvider
{

    /**
     *
     */
    public function boot()
    {

        require __DIR__.'/../Http/routes.php';

//        DB::listen(function($sql, $bindings) {
//            var_dump($sql);
//        });

        // $this->loadViewsFrom(__DIR__.'/resources/views', 'cms');

        // $this->publishes([
        //    __DIR__.'/../config/cms.php' => config_path('cms.php'),
        // ], 'config');
        // $this->publishes([
        //     __DIR__.'/../resources/views' => base_path('resources/views'),
        // ], 'resources');

        // $this->publishes([
        //     __DIR__.'/../resources/assets' => base_path('resources/assets'),
        // ], 'assets');
        // $this->publishes([
        //     __DIR__.'/../database/migrations/' => database_path('migrations')
        // ], 'migrations');

        // $this->publishes([
        //     __DIR__.'/../resources/assets/js/vendor' => base_path('public/js/vendor')
        // ], 'js');


//        parent::registerPolicies($gate);
//
//        $gate->define('cms:writer', function ($user) {
//            $allowed = array(
//                'cms_writer',
//                'cms_support',
//                'cms_store_admin',
//                'cms_admin',
//            );
//
//            return in_array($user->user_level, $allowed);
//        });
//
//        $gate->define('cms:support', function ($user) {
//            $allowed = array(
//                'cms_support',
//                'cms_store_admin',
//                'cms_admin',
//            );
//
//            return in_array($user->user_level, $allowed);
//        });
//
//        $gate->define('cms:store_admin', function ($user) {
//            $allowed = array(
//                'cms_store_admin',
//                'cms_admin',
//            );
//
//            return in_array($user->user_level, $allowed);
//        });
//
//        $gate->define('cms:site_admin', function ($user) {
//            $allowed = array(
//                'cms_admin'
//            );
//
//            return in_array($user->user_level, $allowed);
//        });
    }

    /**
     *
     */
    public function register()
    {

        $app = $this->app;
        
        $app['cmsImage'] = $app->share(function ($app) {
            return new \Cms\Managers\ImageManger();
        });

        $app->alias('cmsImage', '\Cms\Managers\ImageManager');
    }
}
