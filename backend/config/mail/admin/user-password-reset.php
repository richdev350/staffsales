<?php

return [

    /*
    |--------------------------------------------------------------------------
    | User Password Reset Mail
    |--------------------------------------------------------------------------
    |
    */

    'subject'       => 'パスワード再設定のリクエストを受け付けました',
    'from'          => [
        'address' => env('MAIL_FROM_ADDRESS', 'noreply@hoge.hoge'),
        'name'    => env('MAIL_FROM_ADDRESS', 'コスモス薬品ケーキ販売'),
    ],
    'return-path'   => env('MAIL_RETURN_PATH', 'noreply@hoge.hoge'),
    'envelope-from' => env('MAIL_ENVELOPE_FROM', 'noreply@hoge.hoge'),

];
