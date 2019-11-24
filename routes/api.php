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
        Route::post('/', 'ProductController@store');
        Route::post('/upload-image', 'ProductController@uploadImage');
        Route::get('/search', 'ProductController@search');
    });
    
    Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
        Route::get('/', 'CategoryController@index');
    });

    Route::group(['prefix' => 'brand', 'as' => 'brand.'], function () {
        Route::get('/', 'BrandController@index');
    });
});
