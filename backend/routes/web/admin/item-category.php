<?php
declare(strict_types=1);

Route::group(['middleware' => ['permission:admin_permission']], function () {
    Route::group(['prefix' => 'item-category', 'as' => 'item-category.'], function () {
        Route::get('{condition?}', 'ItemCategoryController@list')
            ->name('list');
        
    });
});
