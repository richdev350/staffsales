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
 *販売終了画面
*/
Route::group(['prefix' => 'salesend', 'as' => 'salesend.'], function () {
    Route::get('end', 'SalesendController@end')
        ->name('end');
});


