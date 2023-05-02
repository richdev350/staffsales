<?php
declare(strict_types=1);

Route::group(['middleware' => ['permission:admin_permission']], function () {
    Route::group(['prefix' => 'desired-time', 'as' => 'desired-time.'], function () {
        Route::delete('destroy/{id}', 'DesiredTimeController@destroy')
            ->where(['id' => '^\d+$'])
            ->name('destroy');

        Route::get('show/{id}', 'DesiredTimeController@show')
            ->where(['id' => '^\d+$'])
            ->name('show');

        Route::match(['put', 'patch'], 'edit/{id}', 'DesiredTimeController@update')
            ->where(['id' => '^\d+$'])
            ->name('update');
        Route::get('edit/{id}', 'DesiredTimeController@edit')
            ->where(['id' => '^\d+$'])
            ->name('edit');

        Route::post('create', 'DesiredTimeController@store')
            ->name('store');
        Route::get('create', 'DesiredTimeController@create')
            ->name('create');

        Route::post('batch/{condition?}', 'DesiredTimeController@batch')
            ->name('batch');

        Route::post('', 'DesiredTimeController@select')
            ->name('select');

        Route::get('{condition?}', 'DesiredTimeController@list')
            ->name('list');
    });
});
