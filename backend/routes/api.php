<?php
declare(strict_types=1);

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

Route::group(['namespace' => 'Api', 'middleware' => 'cors'], function () {
    Route::group(['prefix' => 'item-category', 'as' => 'item-category.'], function () {
        Route::get('{condition?}', 'ItemCategoryController@list')
        ->name('list');

        Route::delete('destroy', 'ItemCategoryController@destroy')
            ->name('destroy');

        Route::match(['put', 'patch'], 'edit', 'ItemCategoryController@update')
        ->name('update');

        Route::post('create', 'ItemCategoryController@store')
            ->name('store');

    });
    Route::group(['prefix' => 'image', 'as' => 'image.'], function () {
        Route::post('upload', 'UploadController@upload')
            ->name('upload');
    });
    Route::group(['prefix' => 'cart', 'as' => 'cart.'], function () {
        Route::post('change', 'CartController@change')
            ->name('change');
        Route::post('add', 'CartController@add')
            ->name('add');
        Route::delete('delete', 'CartController@delete')
            ->name('delete');
    });
    Route::group(['prefix' => 'search-item', 'as' => 'search-item.'], function () {
        Route::post('search', 'ItemController@search')
            ->name('search');
    });
    Route::group(['prefix' => 'publish', 'as' => 'publish.'], function () {
        Route::get('publish', 'ItemController@publish')
            ->name('publish');
    });
});

Route::group(['namespace' => 'Api', 'middleware' => 'check.ip'], function () {
    Route::group(['prefix' => 'order', 'as' => 'order.'], function () {
        Route::get('show', 'OrderController@show')
            ->name('show');
    });
});
