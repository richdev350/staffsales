<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
|--------------------------------------------------------------------------
| 一般ユーザー（店舗）向けルーティング
|--------------------------------------------------------------------------
*/
Route::group(['namespace' => 'General'], function () {
    load_php_files(__DIR__ . '/web/general');
});

/**
 * 管理画面用ルーティング
 */
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::group(['namespace' => 'Auth', 'as' => 'auth.'], function () {
        Route::group(['middleware' => 'guest:admin'], function () {
            // パスワード忘れ/リセット
            /*
            Route::match(['get', 'post'],'reset-password/{token}', 'PasswordController@reset')
                ->name('reset-password');
            Route::post('request-password', 'PasswordController@request')
                ->name('request-password');
            Route::get('forgot-password', 'PasswordController@forgot')
                ->name('forgot-password');
            */
            // ログイン
            Route::post('login', 'AuthController@authenticate')
                ->name('authenticate');
            Route::get('login', 'AuthController@login')
                ->name('login');
        });

        // ログアウト
        Route::match(['get', 'post'], 'logout', 'AuthController@logout')
            ->name('logout');
    });

    Route::group(['middleware' => 'auth:admin'], function () {
        // ダッシュボード
        Route::group(['middleware' => ['permission:admin_permission|manager_permission']], function () {
            Route::get('home', 'HomeController@index')->name('home');
            Route::get('', 'HomeController@index')->name('home');
        });
        load_php_files(__DIR__ . '/web/admin');
    });
});