<?php

//Route::group(['prefix' => '_debugbar', 'middleware' => ['web', 'auth']], function() {
//    Route::get('assets/stylesheets', [
//        'as' => 'debugbar-css',
//        'uses' => '\Barryvdh\Debugbar\Controllers\AssetController@css'
//    ]);
//
//    Route::get('assets/javascript', [
//        'as' => 'debugbar-js',
//        'uses' => '\Barryvdh\Debugbar\Controllers\AssetController@js'
//    ]);
//
//    Route::get('open', [
//        'as' => 'debugbar-open',
//        'uses' => '\Barryvdh\Debugbar\Controllers\OpenController@handler'
//    ]);
//});






Route::group(['prefix' => 'api/v1', 'middleware' => ['tenant', 'web', 'auth', 'admin']], function () {

    Route::get('dashboard', '\Cms\Http\Controllers\Cms\Api\DashboardController@index');
    Route::resource('route', '\Cms\Http\Controllers\Cms\Api\RouteController', ['except' => ['create', 'edit']]);
    Route::resource('user', '\Cms\Http\Controllers\Cms\Api\UserController', ['except' => ['create', 'edit']]);
    Route::resource('extension', '\Cms\Http\Controllers\Cms\Api\ExtensionController', ['except' => ['create', 'edit']]);
    Route::resource('category', '\Cms\Http\Controllers\Cms\Api\CategoryController', ['except' => ['create', 'edit']]);
    Route::resource('asset', '\Cms\Http\Controllers\Cms\Api\AssetController', ['except' => ['create', 'edit']]);
    Route::resource('cropsize', '\Cms\Http\Controllers\Cms\Api\CropSizeController', ['except' => ['create', 'edit']]);
    Route::resource('image', '\Cms\Http\Controllers\Cms\Api\ImageController', ['except' => ['create', 'edit']]);
    Route::resource('asset', '\Cms\Http\Controllers\Cms\Api\AssetController', ['except' => ['create', 'edit']]);
    Route::resource('block', '\Cms\Http\Controllers\Cms\Api\BlockController', ['except' => ['create', 'edit']]);
    Route::resource('newsfeed', '\Cms\Http\Controllers\Cms\Api\NewsFeedController', ['except' => ['create', 'edit']]);
    Route::resource('newsitem', '\Cms\Http\Controllers\Cms\Api\NewsItemController', ['except' => ['create', 'edit']]);
    Route::post('newsitem/order', '\Cms\Http\Controllers\Cms\Api\NewsItemController@reorder');

    Route::resource('gallery', '\Cms\Http\Controllers\Cms\Api\GalleryController', ['except' => ['create', 'edit']]);
    Route::resource('section', '\Cms\Http\Controllers\Cms\Api\SectionController', ['except' => ['create', 'edit']]);

//    Route::resource('product', '\Cms\Http\Controllers\Cms\Api\ProductController', ['except' => ['create', 'edit']]);
    Route::resource('content', '\Cms\Http\Controllers\Cms\Api\ContentController', ['except' => ['create', 'edit']]);
    Route::post('content/order', '\Cms\Http\Controllers\Cms\Api\ContentController@reorder');

    Route::resource('event', '\Cms\Http\Controllers\Cms\Api\EventController', ['except' => ['create', 'edit']]);

    Route::resource('form', '\Cms\Http\Controllers\Cms\Api\FormController', ['except' => ['create', 'edit']]);
    Route::post('form/newblock', '\Cms\Http\Controllers\Cms\Api\FormController@storeBlock');

    Route::resource('formfield', '\Cms\Http\Controllers\Cms\Api\FormFieldController', ['except' => ['create', 'edit']]);
    Route::get('form/{id}/export/{type}', '\Cms\Http\Controllers\Cms\Api\FormExportController@export');
    Route::get('form/{id}/submission', '\Cms\Http\Controllers\Cms\Api\FormSubmissionController@index');
    Route::get('form/{id}/submission/{subId}', '\Cms\Http\Controllers\CmsApi\FormSubmissionController@show');

    Route::get('contenttemplate/{key}', '\Cms\Http\Controllers\Cms\Api\ContentTemplateController@showByKey');
    Route::resource('template', '\Cms\Http\Controllers\Cms\Api\TemplateController', ['only' => 'index']);
    Route::resource('page', '\Cms\Http\Controllers\Cms\Api\PageController', ['except' => ['create', 'edit']]);
    Route::post('page/newblock', '\Cms\Http\Controllers\Cms\Api\PageController@storeBlock');
    Route::put('page/{id}/publish', '\Cms\Http\Controllers\Cms\Api\PageController@publish');
    Route::put('page/{id}/hide', '\Cms\Http\Controllers\Cms\Api\PageController@hide');
    Route::put('page/{id}/schedule', '\Cms\Http\Controllers\Cms\Api\PageController@schedule');

});


Route::group(['prefix' => 'img', 'middleware' => ['tenant', 'web']], function () {
    Route::get('cropped/{siteFolder}/{year}/{month}/{date}/{dir1}/{id}/{name}_{filename}', '\Cms\Http\Controllers\Cms\ImageController@renderPublicCropExtended');
});


Route::group(['middleware' => ['tenant', 'web', 'api']], function () {
    Route::get('submit/_token', '\Cms\Http\Controllers\Cms\SubmitController@getToken');
});


Route::group(['middleware' => ['tenant', 'web']], function () {

    Route::group(['middleware' => ['auth', 'admin']], function () {
        Route::get('/admin', '\Cms\Http\Controllers\Cms\AdminController@index');
        Route::get('/admin/image-by-id/{id}', '\Cms\Http\Controllers\Cms\ImageController@showImageById');
        Route::get('/admin/img-preview/{id}', '\Cms\Http\Controllers\Cms\ImageController@showImagePreview');
        Route::get('/admin/img-preview-lg/{id}', '\Cms\Http\Controllers\Cms\ImageController@showImagePreviewLarge');
    });

    Route::post('submit/{id}', '\Cms\Http\Controllers\Cms\SubmitController@submit');

    Route::get(config('cms.blog_path'), '\Cms\Http\Controllers\Cms\BlogController@getIndex');
    Route::get(config('cms.blog_path').'{category}', '\Cms\Http\Controllers\Cms\BlogController@getCategory');
    Route::get(config('cms.blog_path').'{year}/{month}/{day}/{slug}', '\Cms\Http\Controllers\Cms\BlogController@getPost');



    Route::get('/sample', '\Cms\Http\Controllers\SampleController@getSamplePage');

    Route::get('/{all}', '\Cms\Http\Controllers\Cms\UrlController@show')->where('all', '.*');
});