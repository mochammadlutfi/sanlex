<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });


Route::namespace('App\Http\Controllers\Panel')->group(function()
{

    Route::namespace('Auth')->group(function(){

        //Login Routes
        Route::get('/','LoginController@showLoginForm');
        Route::get('/login','LoginController@showLoginForm')->name('login');
        Route::post('/login','LoginController@login')->name('loginPost');
        Route::post('/logout','LoginController@logout')->name('logout');

        //Forgot Password Routes
        Route::get('/password/reset','ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('/password/email','ForgotPasswordController@sendResetLinkEmail')->name('password.email');

        //Reset Password Routes
        Route::get('/password/reset/{token}','ResetPasswordController@showResetForm')->name('password.reset');
        // Route::post('/password/reset','ResetPasswordController@reset')->name('password.update');

        // // // Email Verification Route(s)
        // Route::get('email/verify','VerificationController@show')->name('verification.notice');
        // Route::get('email/verify/{id}','VerificationController@verify')->name('verification.verify');
        // Route::get('email/resend','VerificationController@resend')->name('verification.resend');
    });


    Route::group(['middleware' => 'auth'], function () {
            Route::get('/dashboard','DashboardController@index')->name('dashboard');
            
            Route::group(['prefix' => 'base', 'as'=>'base.'], function () {
                Route::get('/menu', 'BaseController@index')->name('menu');
                Route::get('/daerah', 'BaseController@daerah')->name('daerah');
                Route::get('/kota', 'BaseController@kota')->name('kota');
            });
                
            Route::namespace('Product')->prefix('/product')->name('product.')->group(function () {

                Route::prefix('/category')->name('category.')->group(function () {
                    Route::get('/', 'CategoryController@index')->name('index');
                    Route::post('/store','CategoryController@store')->name('store');
                    Route::get('/data', 'CategoryController@data')->name('data');
                    Route::get('/tree', 'CategoryController@tree')->name('tree');
                    Route::post('/{id}/update','CategoryController@update')->name('update');
                    Route::delete('/{id}/delete','CategoryController@destroy')->name('delete');
                });

                Route::prefix('/brand')->name('brand.')->group(function () {
                    Route::get('/', 'BrandController@index')->name('index');
                    Route::post('/store','BrandController@store')->name('store');
                    Route::get('/data', 'BrandController@data')->name('data');
                    Route::post('/{id}/update','BrandController@update')->name('update');
                    Route::delete('/{id}/delete','BrandController@destroy')->name('delete');
                });

                Route::prefix('/packaging')->name('packaging.')->group(function () {
                    Route::get('/', 'PackagingController@index')->name('index');
                    Route::post('/store','PackagingController@store')->name('store');
                    Route::get('/data', 'PackagingController@data')->name('data');
                    Route::put('/{id}/update','PackagingController@update')->name('update');
                    Route::delete('/{id}/delete','PackagingController@destroy')->name('delete');
                });
                
                Route::prefix('/feature')->name('feature.')->group(function () {
                    Route::get('/', 'FeatureController@index')->name('index');
                    Route::get('/data', 'FeatureController@data')->name('data');
                    Route::post('/store','FeatureController@store')->name('store');
                    Route::post('/{id}/update','FeatureController@update')->name('update');
                    Route::delete('/{id}/delete','FeatureController@destroy')->name('delete');
                });

                Route::prefix('/color')->name('color.')->group(function () {
                    Route::get('/', 'ColorController@index')->name('index');
                    Route::get('/data','ColorController@data')->name('data');
                    Route::post('/store','ColorController@store')->name('store');
                    Route::put('/{id}/update','ColorController@update')->name('update');
                    Route::delete('/{id}/delete','ColorController@destroy')->name('delete');
                });


                Route::get('/', 'ProductController@index')->name('index');
                Route::get('/data', 'ProductController@data')->name('data');
                Route::get('/create', 'ProductController@create')->name('create');
                Route::post('/store','ProductController@store')->name('store');
                Route::get('/{id}/edit','ProductController@edit')->name('edit');
                Route::post('/{id}/update','ProductController@update')->name('update');
                Route::get('/{id}/delete','ProductController@delete')->name('delete');
                Route::get('/{id}/variant','ProductController@variant')->name('variant');
                
                Route::prefix('/{productId}/price')->name('price.')->group(function () {
                    Route::get('/', 'PriceController@index')->name('index');
                    Route::get('/data','PriceController@data')->name('data');
                    Route::post('/store','PriceController@store')->name('store');
                });
            });

            Route::namespace('Post')->prefix('/post')->name('post.')->group(function () {

                Route::prefix('/category')->name('category.')->group(function () {
                    Route::get('/', 'CategoryController@index')->name('index');
                    Route::get('/create', 'CategoryController@create')->name('create');
                    Route::post('/store','CategoryController@store')->name('store');
                    Route::get('/data', 'CategoryController@data')->name('data');
                    Route::get('/tree', 'CategoryController@tree')->name('tree');
                    Route::get('/{id}', 'CategoryController@show')->name('show');
                    Route::get('/{id}/edit','CategoryController@edit')->name('edit');
                    Route::post('/{id}/update','CategoryController@update')->name('update');
                    Route::delete('/{id}/delete','CategoryController@destroy')->name('delete');
                });

                Route::get('/', 'PostController@index')->name('index');
                Route::get('/create', 'PostController@create')->name('create');
                Route::post('/store','PostController@store')->name('store');
                Route::get('/data', 'PostController@data')->name('data');
                Route::get('/{id}/edit','PostController@edit')->name('edit');
                Route::post('/{id}/update','PostController@update')->name('update');
                Route::get('/{id}/delete','PostController@delete')->name('delete');
            });
            
            Route::prefix('/branch')->name('branch.')->group(function () {
                Route::get('/', 'BranchController@index')->name('index');
                Route::get('/create', 'BranchController@create')->name('create');
                Route::post('/store','BranchController@store')->name('store');
                Route::get('/data', 'BranchController@data')->name('data');
                Route::get('/{id}/edit', 'BranchController@edit')->name('edit');
                Route::post('/{id}/update','BranchController@update')->name('update');
                Route::delete('/{id}/delete','BranchController@destroy')->name('delete');
            });

            Route::prefix('/customer')->name('customer.')->group(function () {
                Route::get('/', 'CustomerController@index')->name('index');
                Route::get('/create', 'CustomerController@create')->name('create');
                Route::post('/store','CustomerController@store')->name('store');
                Route::get('/data', 'CustomerController@data')->name('data');
                Route::get('/{id}/edit','CustomerController@edit')->name('edit');
                Route::post('/{id}/update','CustomerController@update')->name('update');
                Route::delete('/{id}/delete','CustomerController@destroy')->name('delete');
            });

            Route::prefix('/certification')->name('certification.')->group(function () {
                Route::get('/', 'CertificationController@index')->name('index');
                Route::post('/store','CertificationController@store')->name('store');
                Route::get('/data', 'CertificationController@data')->name('data');
                Route::get('/tree', 'CertificationController@tree')->name('tree');
                Route::post('/{id}/update','CertificationController@update')->name('update');
                Route::delete('/{id}/delete','CertificationController@destroy')->name('delete');
            });

            Route::prefix('/media')->name('media.')->group(function () {
                Route::get('/', 'MediaController@index')->name('index');
                Route::get('/create', 'MediaController@create')->name('create');
                Route::post('/store','MediaController@store')->name('store');
                Route::get('/edit/{id}','MediaController@edit')->name('edit');
                Route::post('/update','MediaController@update')->name('update');
                Route::get('/delete/{id}','MediaController@delete')->name('delete');
                Route::get('/data', 'MediaController@data')->name('data');
            });
            
            Route::namespace('Project')->prefix('/project')->name('project.')->group(function () {

            //     Route::prefix('/portofolio')->name('portofolio.')->group(function () {
            //         Route::get('/', 'PortofolioController@index')->name('index');
            //         Route::get('/create', 'PortofolioController@create')->name('create');
            //         Route::post('/store','PortofolioController@store')->name('store');
            //         Route::get('/data', 'PortofolioController@data')->name('data');
            //         Route::get('/tree', 'PortofolioController@tree')->name('tree');
            //         Route::get('/{id}', 'PortofolioController@show')->name('show');
            //         Route::get('/{id}/edit','PortofolioController@edit')->name('edit');
            //         Route::post('/{id}/update','PortofolioController@update')->name('update');
            //         Route::delete('/{id}/delete','PortofolioController@destroy')->name('delete');
            //     });
                
                Route::prefix('/contact')->name('contact.')->group(function () {
                    Route::get('/', 'ContactController@index')->name('index');
                    Route::get('/create', 'ContactController@create')->name('create');
                    Route::post('/store','ContactController@store')->name('store');
                    Route::get('/data', 'ContactController@data')->name('data');
                    Route::get('/{id}/edit','ContactController@edit')->name('edit');
                    Route::post('/{id}/update','ContactController@update')->name('update');
                    Route::delete('/{id}/delete','ContactController@destroy')->name('delete');
                });

            });
            
        });

});




// require __DIR__.'/auth.php';
