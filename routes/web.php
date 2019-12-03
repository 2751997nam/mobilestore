<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => '/admin', 'as' => 'admin.', 'namespace' => '\\System'], function () {
    Route::get('/', 'IndexController@index');
    Route::get('/{module}', 'IndexController@module')->name('system::module');
    Route::get('/{module}/{subModule}', 'IndexController@subModule')->name('system::submodule');
});

Route::group(['prefix' => '/', 'namespace' => '\\Frontend', 'middleware' => 'header'], function () {
    Route::get('/', 'HomeController@index')->name('index');
    Route::get('/search', 'ProductController@search')->name('product.search');
    Route::get('/{slug}-p-{id}', 'ProductController@show')->name('product.detail')->where('slug', '[0-9a-zA-Z/_\-]+')->where('id', '[0-9]+');
    Route::get('/{slug}-c-{id}', 'ProductController@search')->name('product.category')->where('slug', '[0-9a-zA-Z/_\-]+')->where('id', '[0-9]+');
    Route::get('/preview-cart', 'CartController@previewCart')->name('cart.preview');
    Route::post('/add-to-cart', 'CartController@store')->name('cart.store');
    Route::get('/cart', 'CartController@index')->name('cart.index');
    Route::get('/checkout', 'CartController@checkout')->name('checkout');
    Route::post('/order', 'OrderController@store')->name('order.store');
    Route::post('/recent-viewed', 'HomeController@recentViewed')->name('home.recent_viewed');

});

Auth::routes();
