<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| 管理画面用ルーティング
|--------------------------------------------------------------------------
|
| All route names are prefixed with 'admin.'.
|
*/

/**
 * 運営アカウント
 */
Route::group(['middleware' => ['permission:admin_permission']], function () {
    Route::group(['prefix' => 'admin-user', 'as' => 'admin-user.'], function () {
        // 削除
        Route::delete('destroy/{id}', 'AdminUserController@destroy')
            ->where(['id' => '^\d+$'])
            ->name('destroy');

        // 詳細
        Route::get('show/{id}', 'AdminUserController@show')
            ->where(['id' => '^\d+$'])
            ->name('show');

        // 編集
        Route::match(['put', 'patch'], 'edit/{id}', 'AdminUserController@update')
            ->where(['id' => '^\d+$'])
            ->name('update');
        Route::get('edit/{id}', 'AdminUserController@edit')
            ->where(['id' => '^\d+$'])
            ->name('edit');

        // 新規登録
        Route::post('create', 'AdminUserController@store')
            ->name('store');
        Route::get('create', 'AdminUserController@create')
            ->name('create');

        // 一括処理
        Route::post('batch/{condition?}', 'AdminUserController@batch')
            ->name('batch');

        // 抽出
        Route::post('', 'AdminUserController@select')
            ->name('select');

        // 一覧
        Route::get('{condition?}', 'AdminUserController@list')
            ->name('list');
    });
});
