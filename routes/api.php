<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


/**
 * Healthcheck
 *
 * Check that the service is up. If everything is okay, you'll get a 200 OK response.
 *
 * Otherwise, the request will fail with a 400 error, and a response listing the failed services.
 *
 * @response 400 scenario="Service is unhealthy" {"status": "down", "services": {"database": "up", "redis": "down"}}
 * @responseField status The status of this API (`up` or `down`).
 * @responseField services Map of each downstream service and their status (`up` or `down`).
 */
Route::get('/healthcheck', function () {
    return [
        'status' => 'up',
        'services' => [
            'database' => 'up',
            'redis' => 'up',
        ],
    ];
});


Route::namespace('App\Http\Controllers\API')->name('api.')->group(function () {

    Route::namespace('v1')->group(function () {
        Route::post('/login','AuthController@login')->name('login');
        Route::post('/logout','AuthController@logout')->name('logout');

        
        Route::prefix('/product')->name('product.')->group(function () {
            
            Route::get('/', 'ProductController@index')->name('index');
            Route::get('/category', 'ProductController@category')->name('category');
            Route::get('/brand', 'ProductController@brand')->name('brand');
            Route::get('/{slug}', 'ProductController@show')->name('show');
        });

        Route::get('/certification', 'BaseController@certification')->name('certification');
    
        Route::prefix('/product')->name('product.')->group(function () {
            
            Route::get('/', 'ProductController@index')->name('index');
            Route::get('/category', 'ProductController@category')->name('category');
            Route::get('/brand', 'ProductController@brand')->name('brand');
            Route::get('/{slug}', 'ProductController@show')->name('show');
        });
        
        Route::prefix('/project')->name('project.')->group(function () {
            Route::get('/', 'ProjectController@index')->name('index');
            Route::get('/contact', 'ProjectController@contact')->name('contact');
            Route::get('/{slug}', 'ProjectController@show')->name('show');
        });
    
        Route::get('/branch', 'ReachController@branch')->name('branch');
        Route::get('/retail', 'ReachController@retail')->name('retail');
    
        Route::prefix('/post')->name('post.')->group(function () {
            Route::get('/', 'PostController@index')->name('index');
            Route::get('/{slug}', 'PostController@show')->name('show');
        });
    });
});
