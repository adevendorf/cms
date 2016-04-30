<?php
namespace Cms\Providers;

use Illuminate\Support\ServiceProvider;

class CmsServiceProvider extends ServiceProvider
{
    public function boot()
    {

        require __DIR__.'/../Http/routes.php';

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

    }

    public function register()
    {
        $this->app->singleton('CmsRepositoryManager', function ($app) {
            $manager = '\\Cms\\Managers\\System\\RepositoryManager';
            return new $manager();
        });

        $this->app->singleton('CmsImageManager', function ($app) {
            $manager = '\\Cms\\Managers\\System\\ImageManager';
            return new $manager();
        });
    }
}
