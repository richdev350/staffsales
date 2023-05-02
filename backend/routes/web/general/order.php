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
 * 注文者情報入力
*/

Route::group(['prefix' => 'order', 'as' => 'order.'], function () {
    // フォーム
    Route::get('form', 'OrderController@create')
        ->name('form');
    // 確認
    Route::post('confirm', 'OrderController@store')
        ->name('confirm');
    // 注文完了
    Route::get('thanks', 'OrderController@thanks')
        ->name('thanks');

});
