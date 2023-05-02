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
 * メーカーアカウント
 */
Route::group(['middleware' => ['permission:admin_permission']], function () {
    Route::group(['prefix' => 'maker', 'as' => 'maker.'], function () {
        // 削除
        Route::delete('destroy/{id}', 'MakerController@destroy')
            ->where(['id' => '^\d+$'])
            ->name('destroy');

        // 詳細
        Route::get('show/{id}', 'MakerController@show')
            ->where(['id' => '^\d+$'])
            ->name('show');

        // 編集
        Route::match(['put', 'patch'], 'edit/{id}', 'MakerController@update')
            ->where(['id' => '^\d+$'])
            ->name('update');
        Route::get('edit/{id}', 'MakerController@edit')
            ->where(['id' => '^\d+$'])
            ->name('edit');

        // 新規登録
        Route::post('create', 'MakerController@store')
            ->name('store');
        Route::get('create', 'MakerController@create')
            ->name('create');

        // 一括処理
        Route::post('batch/{condition?}', 'MakerController@batch')
            ->name('batch');

        // 抽出
        Route::post('', 'MakerController@select')
            ->name('select');

        // 一覧
        Route::get('{condition?}', 'MakerController@list')
            ->name('list');
    });
});