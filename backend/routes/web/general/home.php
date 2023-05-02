<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| 一般向けルーティング
|--------------------------------------------------------------------------
|
|
*/

/**
 * ホーム
*/
Route::group(['as' => 'home.'], function () {
    Route::get('', 'HomeController@index')
    ->name('index');

    // TOP 一覧
    Route::get('list', 'HomeController@list')
    ->name('list');

    // Delete Barcode
    Route::delete('barcode/{barcode}', 'HomeController@destroyBarcode')
    ->name('barcode.destroy');
});
        
