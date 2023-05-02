<?php
declare(strict_types=1);

Route::group(['middleware' => ['permission:admin_permission']], function () {
    Route::group(['prefix' => 'shop', 'as' => 'shop.'], function () {
        Route::delete('destroy/{id}', 'ShopController@destroy')
            ->where(['id' => '^\d+$'])
            ->name('destroy');

        Route::get('show/{id}', 'ShopController@show')
            ->where(['id' => '^\d+$'])
            ->name('show');

        Route::match(['put', 'patch'], 'edit/{id}', 'ShopController@update')
            ->where(['id' => '^\d+$'])
            ->name('update');
        Route::get('edit/{id}', 'ShopController@edit')
            ->where(['id' => '^\d+$'])
            ->name('edit');

        Route::post('create', 'ShopController@store')
            ->name('store');
        Route::get('create', 'ShopController@create')
            ->name('create');

        Route::post('batch/{condition?}', 'ShopController@batch')
            ->name('batch');

        Route::post('', 'ShopController@select')
            ->name('select');

        Route::get('{condition?}', 'ShopController@list')
            ->name('list');
    });
});
