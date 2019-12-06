<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => '/', 'namespace' => '\\Api'], function () {
    Route::group(['prefix' => 'product'], function () {
        Route::get('/', 'ProductController@index');
        Route::get('/{id}', 'ProductController@show')->where('id', '[0-9]+');
        Route::put('/{id}', 'ProductController@update')->where('id', '[0-9]+');
        Route::post('/', 'ProductController@store');
        Route::post('/upload-image', 'ProductController@uploadImage');
        Route::get('/search', 'ProductController@search');
    });
    
    Route::group(['prefix' => 'category'], function () {
        Route::get('/', 'CategoryController@index');
        Route::post('/', 'CategoryController@store');
        Route::put('/{id}', 'CategoryController@update')->where('id', '[0-9]+');
        Route::delete('/{id}', 'CategoryController@delete')->where('id', '[0-9]+');
        Route::post('/upload-image', 'CategoryController@uploadImage');
    });

    Route::group(['prefix' => 'brand'], function () {
        Route::get('/', 'BrandController@index');
        Route::post('/', 'BrandController@store');
        Route::put('/{id}', 'BrandController@update')->where('id', '[0-9]+');
        Route::delete('/{id}', 'BrandController@delete')->where('id', '[0-9]+');
        Route::post('/upload-image', 'BrandController@uploadImage');
    });

    Route::group(['prefix' => 'customer'], function () {
        Route::get('/', 'CustomerController@index');
        Route::get('/{id}', 'CustomerController@show')->where('id', '[0-9]+');
        Route::post('/', 'CustomerController@store');
        Route::put('/{id}', 'CustomerController@update')->where('id', '[0-9]+');
        Route::delete('/{id}', 'CustomerController@delete')->where('id', '[0-9]+');
        Route::post('/upload-image', 'CustomerController@uploadImage');
    });

    Route::get('/province', 'LocationController@getProvinces');
    Route::get('/district', 'LocationController@getDistricts');
    Route::get('/commune', 'LocationController@getCommunes');
    Route::get('/order', 'OrderController@index');
    Route::post('/order', 'OrderController@store')->name('api.order.store');
    Route::get('/order/{id}', 'OrderController@show')->where('id', '[0-9]+');
    ROute::put('/order/{id}', 'OrderController@update')->where('id', '[0-9]+');
    Route::patch('/order/{id}', 'OrderController@updateStatus')->where('id', '[0-9]+');
});
