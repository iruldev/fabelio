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


Route::group(['namespace' => 'Product'], function () {
    Route::get('/', 'ProductController@index')->name('product.list');
    Route::get('new', 'ProductController@create')->name('product.new');
    Route::get('detail/{id}', 'ProductController@show')->name('product.detail');

    Route::post('new', 'ProductController@store')->name('product.store');
});

Route::group(['namespace' => 'Ajax'], function () {
    Route::post('upload-image', 'UploadController@uploadImageProduct')->name('ajax.upload.image');
    Route::post('get-image', 'UploadController@getImageProduct')->name('ajax.get.image');
});
