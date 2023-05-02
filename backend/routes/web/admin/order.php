<?php
declare(strict_types=1);

Route::group(['middleware' => ['permission:manager_permission']], function () {
    Route::group(['middleware' => ['permission:admin_permission']], function () {
        Route::group(['prefix' => 'order', 'as' => 'order.'], function () {
            Route::match(['put', 'patch'], 'edit/{id}', 'OrderController@update')
                ->where(['id' => '^\d+$'])
                ->name('update');

            Route::get('edit/{id}', 'OrderController@edit')
                ->where(['id' => '^\d+$'])
                ->name('edit');

            Route::post('batch/{condition?}', 'OrderController@batch')
                ->name('batch');

            Route::get('export/{condition?}', 'OrderController@export')
                ->name('export');
        });
    });

    Route::group(['prefix' => 'order', 'as' => 'order.'], function () {
        Route::delete('destroy/{id}', 'OrderController@destroy')
            ->where(['id' => '^\d+$'])
            ->name('destroy');

        Route::get('show/{id}', 'OrderController@show')
            ->where(['id' => '^\d+$'])
            ->name('show');

        Route::post('', 'OrderController@select')
            ->name('select');

        Route::get('{condition?}', 'OrderController@list')
            ->name('list');
    });
});
