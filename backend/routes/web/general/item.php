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
 * 商品
*/

Route::group(['prefix' => 'item', 'as' => 'item.'], function () {
    // 詳細
    Route::get('detail/{item_category_id}/{id}', 'ItemController@detail')
        ->where(['item_category_id' => '^\d+$', 'id' => '^\d+$'])
        ->name('detail');

    Route::post('', 'ItemController@select')
        ->name('select');

    // 一覧
    Route::get('list/{item_category_id?}', 'ItemController@list')
        ->where(['item_category_id' => '^\d+$'])
        ->name('list');

    Route::get('{condition?}', 'ItemController@allList')
        ->name('all');
});
