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
        /**
        * Customer Login
        * 
        * Melakukan autentikasi customer dan mengembalikan token akses.
        * 
        * @bodyParam email string required Email customer. Example: user@example.com
        * @bodyParam password string required Password customer. Example: password123
        * 
        * @response 200 scenario="Login berhasil" {"token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."}
        * @response 401 scenario="Login gagal" {"message": "Unauthorized"}
        * @responseField token Token akses yang digunakan untuk autentikasi.
        */       
        Route::post('/login','AuthController@login')->name('login');
        Route::post('/logout','AuthController@logout')->name('logout');

        Route::middleware(['auth:sanctum'])->group(function () {

            Route::prefix('/profile')->name('profile.')->group(function () {
                Route::get('/', 'ProfileController@index')->name('index');
                Route::post('/update','ProfileController@update')->name('update');
                Route::post('/password','ProfileController@password')->name('password');
                Route::get('/device', 'ProfileController@device')->name('device');
                Route::post('/device/disconect','ProfileController@deviceDisconnect')->name('device.disconect');
            });
            
            Route::prefix('/product')->name('product.')->group(function () {    
                Route::get('/', 'ProductController@index')->name('index');
                Route::get('/top', 'ProductController@top')->name('top');
                Route::get('/category', 'ProductController@category')->name('category');
                Route::get('/brand', 'ProductController@brand')->name('brand');
                Route::get('/{id}', 'ProductController@show')->name('show');
            });

            
            Route::prefix('/cart')->name('cart.')->group(function () {
                Route::get('/', 'CartController@index')->name('index');
                Route::post('/store', 'CartController@store')->name('store');
                Route::post('/{id}/increase', 'CartController@increase')->name('increase');
                Route::post('/{id}/decrease', 'CartController@decrease')->name('decrease');
                Route::delete('/{id}/delete', 'CartController@destroy')->name('delete');
            });

            Route::prefix('/order')->name('order.')->group(function () {
                Route::get('/', 'OrderController@index')->name('index');
                Route::post('/store', 'OrderController@store')->name('store');
                Route::get('/{id}', 'OrderController@show')->name('show');
            });

            Route::prefix('/invoice')->name('invoice.')->group(function () {
                Route::get('/', 'InvoiceController@index')->name('index');
                Route::get('/{id}', 'InvoiceController@show')->name('show');
            });

            Route::prefix('/payment')->name('payment.')->group(function () {
                Route::get('/', 'PaymentController@index')->name('index');
                Route::get('/{id}', 'PaymentController@show')->name('show');
            });
            
            Route::prefix('/return')->name('return.')->group(function () {
                Route::get('/', 'ReturnController@index')->name('index');
                Route::post('/store', 'ReturnController@store')->name('store');
                Route::get('/{id}', 'ReturnController@show')->name('show');
            });

        });
    });
});
