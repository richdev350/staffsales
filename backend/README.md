# コスモス薬品ECサイト構築

コスモス薬品ECサイト構築のためのLaravelプロジェクトです。
- Laravel 6.*
- PHP 7.3 ~
- PostgreSQL ? MySQL ?

## 初期設定

gitリポジトリからdevelopブランチをチェックアウト

### .envファイルを生成

.env.exampleを複製して.envを生成

必要に応じてDB情報を変更する

### コマンド実行
```bash
$ cd [プロジェクトのフォルダ]
$ composer install
$ php artisan key:generate
$ php artisan migrate
$ php artisan db:seed

## 機能構築の大まかな流れ

### テーブル定義作成
database\migrations 以下に必要に応じてcreate table等を登録

マイグレーションを追加した後は毎回
```bash
$ php artisan migrate
```
を実行してDBの定義を更新する

※Laravelは複合キーに基本的に対応していないため、単純に親子関係のテーブル構造を採用する必要あり
※多対多の場合は中間テーブルを利用する

### バリデーションとフィルタの作成
app\Http\Requests\[テーブル名]

以下に処理別のバリデーション[処理内容]Request.php
以下に処理別のフィルタ[処理内容]RequestFilter.php
を記述

※独自バリデーションは
app\Validator.php
に定義する

### modelの構築
app\Models\Entities
以下にテーブルのリレーション、アクセサ、ミューテータを定義

app\Models\Entities\Repositories\Contracts
以下にテーブルの独自処理のメソッドを定義（RepositoryInterface）

app\Models\Entities\Repositories\Eloquent
以下にテーブルの独自処理のメソッドの実体を作成、独自の検索条件処理を追記（Repository）

app\Providers\RepositoryServiceProvider.php
に、RepositoryInterfaceとRepositoryが対となるように追記

### サービスの構築
app\Services\[テーブル名(単数形、一覧とバッチ処理のみ複数形)]
以下に、一覧、バッチ処理、新規登録、更新、削除などのサービスを構築

例：
app\Services\Company\BatchCompaniesService.php
app\Services\Company\CreateCompanyService.php
app\Services\Company\ListCompaniesService.php
app\Services\Company\UpdateCompanyService.php

### トレイト
サービスなどで共通で利用する処理などは
app\Services\Traits 以下に定義する
（認証処理、検索条件のセット、ファイルアップロード、メール送信、バリデーションなど）

### ルーティング設定
総合管理画面では
routes\web\admin

公開側画面では
routes\web\general

以下にコントローラ毎のルーティングを作成
※メソッド名は基本共通とする
※post、getなどのmethodも指定できるので定義されていないmethodでアクセスするとエラーとなる

※公開側のフォルダ名についてはプロジェクトによって柔軟に

### コントローラの構築
処理の実体は前述のサービスで行いコントローラ内で実際の更新処理などは記述せずシンプルに
サービスの実行、view、ルートの指定などを行う

管理画面
app\Http\Controllers\Admin 以下

API
app\Http\Controllers\Api 以下

公開側
app\Http\Controllers\General 以下

### ミドルウエア
app\Http\Middleware
以下にログイン認証などの定義を設定済み
ルーティング時にミドルウエアを呼び出してアクセスの可否を判定する

### blade、メールのテンプレートの作成
resources\views

resources\views\emails

以下に必要に応じてblade、メールのテンプレートを作成

※メール送信処理の場合は
app\Mail\[画面毎のフォルダ]

以下、および
config\mail

以下にメール送信処理の定義を追加（送信元などのアドレス、タイトルなど）

### エラーページの設定
404 Not Foundなどのページは
管理画面
resources\views\admin\errors

公開側
resources\views\general\errors

以下にエラーコード毎のテンプレートを用意し

app\Exeptions\Handler.php
にエラー内容毎に呼び出すテンプレートのパスを指定する。
