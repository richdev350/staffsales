<?php
declare(strict_types=1);

Route::group(['middleware' => ['permission:admin_permission']], function () {
    Route::group(['prefix' => 'publish', 'as' => 'publish.'], function () {

        Route::post('insert', 'PublishController@insert')
            ->name('insert');

        Route::get('create', 'PublishController@create')
            ->name('create');

        Route::get('list', 'PublishController@list')
            ->name('list');
            
        Route::post('update', 'PublishController@update')
            ->name('update');
    });
});
