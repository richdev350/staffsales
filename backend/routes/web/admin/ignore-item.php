<?php
declare(strict_types=1);

Route::group(['middleware' => ['permission:admin_permission']], function () {
    Route::group(['prefix' => 'ignore-item', 'as' => 'ignore-item.'], function () {
        Route::post('batch/{shop_id}', 'IgnoreItemController@batch')
            ->name('batch');

        Route::get('{shop_id}', 'IgnoreItemController@list')
            ->name('list');
    });
});
