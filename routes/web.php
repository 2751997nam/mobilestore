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

Route::group(['prefix' => '/', 'namespace' => '\\Frontend'], function () {
    Route::get('/', 'HomeController@index');
});

Auth::routes();
