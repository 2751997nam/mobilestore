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
    Route::group(['prefix' => 'product', 'as' => 'product.'], function () {
        Route::get('/', 'ProductController@index');
        Route::get('/{id}', 'ProductController@show');
        Route::put('/{id}', 'ProductController@update');
        Route::post('/', 'ProductController@store');
        Route::post('/upload-image', 'ProductController@uploadImage');
        Route::get('/search', 'ProductController@search');
    });
    
    Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
        Route::get('/', 'CategoryController@index');
        Route::post('/', 'CategoryController@store');
        Route::put('/{id}', 'CategoryController@update');
        Route::delete('/{id}', 'CategoryController@delete');
        Route::post('/upload-image', 'CategoryController@uploadImage');
    });

    Route::group(['prefix' => 'brand', 'as' => 'brand.'], function () {
        Route::get('/', 'BrandController@index');
        Route::post('/', 'BrandController@store');
        Route::put('/{id}', 'BrandController@update');
        Route::delete('/{id}', 'BrandController@delete');
        Route::post('/upload-image', 'BrandController@uploadImage');
    });

    Route::group(['prefix' => 'customer', 'as' => 'customer.'], function () {
        Route::get('/', 'CustomerController@index');
        Route::get('/{id}', 'CustomerController@show');
        Route::post('/', 'CustomerController@store');
        Route::put('/{id}', 'CustomerController@update');
        Route::delete('/{id}', 'CustomerController@delete');
        Route::post('/upload-image', 'CustomerController@uploadImage');
    });

    Route::get('/province', 'LocationController@getProvinces');
    Route::get('/district', 'LocationController@getDistricts');
    Route::get('/commune', 'LocationController@getCommunes');
    Route::get('/order', 'OrderController@index');
    Route::post('/order', 'OrderController@store')->name('api.order.store');
    Route::get('/order/{id}', 'OrderController@show');
    ROute::put('/order/{id}', 'OrderController@update');
    Route::patch('/order/{id}', 'OrderController@updateStatus');
});
