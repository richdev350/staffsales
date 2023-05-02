<?php
declare(strict_types=1);

Route::group(['middleware' => ['permission:admin_permission']], function () {
    Route::group(['prefix' => 'item', 'as' => 'item.'], function () {
        Route::delete('destroy/{id}', 'ItemController@destroy')
            ->where(['id' => '^\d+$'])
            ->name('destroy');

        Route::get('show/{id}', 'ItemController@show')
            ->where(['id' => '^\d+$'])
            ->name('show');

        Route::match(['put', 'patch'], 'edit/{id}', 'ItemController@update')
            ->where(['id' => '^\d+$'])
            ->name('update');
        Route::get('edit/{id}', 'ItemController@edit')
            ->where(['id' => '^\d+$'])
            ->name('edit');

        Route::post('create', 'ItemController@store')
            ->name('store');
        Route::get('create', 'ItemController@create')
            ->name('create');

        Route::post('batch/{condition?}', 'ItemController@batch')
            ->name('batch');

        Route::post('sort_exchange', 'ItemController@sortExchange')
            ->name('sort_exchange');

        Route::post('sort', 'ItemController@sort')
            ->name('sort');

        Route::post('', 'ItemController@select')
            ->name('select');

        Route::post('import', 'ItemController@import')
            ->name('import');

        Route::get('export/{condition?}', 'ItemController@export')
            ->name('export');

        Route::get('{condition?}', 'ItemController@list')
            ->name('list');
    });
});
