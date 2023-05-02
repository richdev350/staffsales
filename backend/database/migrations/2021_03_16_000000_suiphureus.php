<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Suiphureus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        }

        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create($tableNames['roles'], function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedBigInteger('permission_id');

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_model_id_model_type_index');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->primary(['permission_id', $columnNames['model_morph_key'], 'model_type'],
                    'model_has_permissions_permission_model_type_primary');
        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedBigInteger('role_id');

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['role_id', $columnNames['model_morph_key'], 'model_type'],
                    'model_has_roles_role_model_type_primary');
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id'], 'role_has_permissions_permission_id_role_id_primary');
        });

        app('cache')
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));

        Schema::create('admin_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('アカウント名');
            $table->string('login_id', 255)->comment('ログインID');
            $table->string('email', 255)->unique()->comment('Email');
            $table->string('password')->comment('ハッシュ化したパスワード');
            $table->string('token', 255)->nullable()->comment('トークン');
            $table->datetime('token_expired_at')->nullable()->comment('トークン有効期限');
            $table->timestamps();
            $table->softDeletes();

            $table->unique('login_id', 'ui_login_id');
            $table->unique('email', 'ui_email');
            $table->unique('token', 'ui_token');
            $table->index('token_expired_at', 'idx_token_expired_at');
        });

        Schema::create('desired_times', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->tinyInteger('from')->unsigned()->comment('時間帯FROM');
            $table->tinyInteger('to')->unsigned()->comment('時間帯TO');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('makers', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->string('name', 255)->comment('メーカー名');
            $table->text('url')->nullable()->comment('リンク先URL');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('regions', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->string('name', 8)->comment('地方名');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('prefectures', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->bigInteger('region_id')->unsigned()->comment('地方ID');
            $table->tinyInteger('code')->unsigned()->comment('都道府県コード');
            $table->string('name', 8)->comment('都道府県名');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('region_id', 'fk_prefectures_region_id')
               ->references('id')->on('regions')
               ->onUpdate('cascade')
               ->onDelete('cascade');
        });

        Schema::create('shops', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->string('code', 255)->comment('店舗コード');
            $table->string('name', 255)->comment('店舗名');
            $table->string('zip_code', 7)->comment('郵便番号');
            $table->bigInteger('prefecture_id')->unsigned()->comment('都道府県ID');
            $table->string('city', 255)->comment('住所(市区町村)');
            $table->string('address', 255)->comment('住所(番地 マンション・ビル名)');
            $table->string('tel', 16)->comment('電話番号');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('prefecture_id', 'fk_shops_prefecture_id')
               ->references('id')->on('prefectures')
               ->onUpdate('cascade')
               ->onDelete('cascade');
        });

        Schema::create('item_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('position', false, true);
            $table->integer('real_depth', false, true)->nullable();
            $table->boolean('class_one')->unsigned()->default(0)->comment('第1類医薬品フラグ');
            $table->softDeletes();

            $table->foreign('parent_id')
              ->references('id')
              ->on('item_categories')
              ->onDelete('set null');
        });

        Schema::create('item_category_closure', function (Blueprint $table) {
            $table->increments('closure_id');

            $table->integer('ancestor', false, true);
            $table->integer('descendant', false, true);
            $table->integer('depth', false, true);

            $table->foreign('ancestor')
              ->references('id')
              ->on('item_categories')
              ->onDelete('cascade');

            $table->foreign('descendant')
              ->references('id')
              ->on('item_categories')
              ->onDelete('cascade');
        });

        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->bigInteger('maker_id')->unsigned()->nullable()->comment('メーカーID');
            $table->string('jan', 13)->nullable()->comment('JANコード');
            $table->string('name', 255)->comment('商品名');
            $table->text('abridge')->nullable()->comment('一覧用商品要約');
            $table->text('summary')->nullable()->comment('商品概要');
            $table->string('description_title', 255)->nullable()->comment('商品説明のタイトル');
            $table->text('description')->nullable()->comment('商品説明');
            $table->json('labels')->nullable()->comment('商品ラベル');
            $table->text('notes')->nullable()->comment('特記事項');
            $table->integer('self_medication')->unsigned()->default(0)->comment('1:対象、0:非対象');
            $table->integer('price')->unsigned()->comment('価格');
            $table->boolean('is_stock')->unsigned()->comment('在庫確認 0:しない、1:する');
            $table->boolean('is_visible')->unsigned()->default(1)->comment('表示・非表示 0:非表示、1:表示');
            $table->json('spec')->nullable()->comment('商品仕様/スペック');
            $table->integer('max_amount')->unsigned()->comment('購入上限数');
            $table->bigInteger('sort_no')->unsigned()->default(0)->comment('ソート順');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('maker_id', 'fk_items_maker_id')
               ->references('id')->on('makers')
               ->onUpdate('cascade')
               ->onDelete('set null');
        });

        Schema::create('item_categories_items', function (Blueprint $table) {
            $table->integer('item_category_id')->unsigned()->comment('商品カテゴリID');
            $table->bigInteger('item_id')->unsigned()->comment('商品ID');
            $table->timestamps();
            $table->primary(['item_category_id', 'item_id']);

            $table->foreign('item_category_id', 'fk_item_categories_items_category_id')
               ->references('id')->on('item_categories')
               ->onUpdate('cascade')
               ->onDelete('cascade');
            $table->foreign('item_id', 'fk_item_categories_items_item_id')
               ->references('id')->on('items')
               ->onUpdate('cascade')
               ->onDelete('cascade');
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->string('name', 255)->comment('商品タグ名');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('items_tags', function (Blueprint $table) {
            $table->bigInteger('item_id')->unsigned()->comment('商品ID');
            $table->bigInteger('tag_id')->unsigned()->comment('商品タグID');
            $table->timestamps();
            $table->primary(['item_id', 'tag_id']);

            $table->foreign('item_id', 'fk_items_tags_item_id')
                ->references('id')->on('items')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('tag_id', 'fk_items_tags_tag_id')
                ->references('id')->on('tags')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('shops_ignore_items', function (Blueprint $table) {
            $table->bigInteger('shop_id')->unsigned()->comment('店舗ID');
            $table->bigInteger('item_id')->unsigned()->comment('商品ID');
            $table->timestamps();
            $table->primary(['shop_id', 'item_id']);

            $table->foreign('shop_id', 'fk_shops_ignore_items_shop_id')
               ->references('id')->on('shops')
               ->onUpdate('cascade')
               ->onDelete('cascade');
            $table->foreign('item_id', 'fk_shops_ignore_items_item_id')
               ->references('id')->on('items')
               ->onUpdate('cascade')
               ->onDelete('cascade');
        });

        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->string('directory', 255)->comment('ディレクトリ');
            $table->string('name', 40)->comment('ファイル名');
            $table->string('mime_type', 255)->comment('MIME Type');
            $table->string('extension', 10)->comment('拡張子');
            $table->integer('size')->unsigned()->comment('ファイルサイズ(byte)');
            $table->integer('width')->unsigned()->nullable()->comment('画像横幅(px)');
            $table->integer('height')->unsigned()->nullable()->comment('画像高さ(px)');
            $table->string('title', 255)->nullable()->comment('画像タイトル');
            $table->string('comment', 255)->nullable()->comment('画像コメント');
            $table->tinyInteger('visible')->unsigned()->default(1)->comment('0:非表示、1:表示');
            $table->timestamps();

            $table->unique(['directory', 'name', 'extension'], 'unique_file_name');
        });

        Schema::create('items_files', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->bigInteger('item_id')->unsigned()->comment('商品ID');
            $table->bigInteger('file_id')->unsigned()->comment('ファイルID');
            $table->integer('sort_no')->unsigned()->comment('ソート順');
            $table->timestamps();

            $table->foreign('item_id', 'fk_items_files_item_id')
               ->references('id')->on('items')
               ->onUpdate('cascade')
               ->onDelete('cascade');
            $table->foreign('file_id', 'fk_items_files_file_id')
               ->references('id')->on('files')
               ->onUpdate('cascade')
               ->onDelete('cascade');
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->string('name', 255)->comment('注文者名');
            $table->string('staff_id')->comment('従業員番号');
            $table->integer('sum')->unsigned()->comment('合計金額');
            $table->string('secure_code')->comment('一意なセキュリティコード');
            $table->timestamps();
            $table->softDeletes();
            $table->unique('secure_code', 'ui_secure_code');
        });

        Schema::create('order_details', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->bigInteger('order_id')->unsigned()->comment('注文ID');
            $table->bigInteger('item_id')->unsigned()->comment('商品ID');
            $table->integer('price')->unsigned()->comment('単価');
            $table->integer('amount')->unsigned()->comment('個数');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('order_id', 'fk_order_details_order_id')
               ->references('id')->on('orders')
               ->onUpdate('cascade')
               ->onDelete('cascade');
            $table->foreign('item_id', 'fk_order_details_item_id')
               ->references('id')->on('items')
               ->onUpdate('cascade')
               ->onDelete('cascade');
        });

        Schema::create('admin_users_shops', function (Blueprint $table) {
            $table->bigInteger('admin_user_id')->unsigned()->comment('ユーザID');
            $table->bigInteger('shop_id')->unsigned()->comment('店舗ID');
            $table->timestamps();
            $table->primary(['admin_user_id', 'shop_id']);

            $table->foreign('admin_user_id')
               ->references('id')->on('admin_users')
               ->onUpdate('cascade')
               ->onDelete('cascade');
            $table->foreign('shop_id')
               ->references('id')->on('shops')
               ->onUpdate('cascade')
               ->onDelete('cascade');
        });

        Schema::create('items_regions', function (Blueprint $table) {
            $table->bigInteger('item_id')->unsigned()->comment('ユーザID');
            $table->bigInteger('region_id')->unsigned()->comment('店舗ID');
            $table->timestamps();
            $table->primary(['item_id', 'region_id']);

            $table->foreign('item_id', 'fk_items_regions_item_id')
                ->references('id')->on('items')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('region_id', 'fk_items_regions_region_id')
                ->references('id')->on('regions')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('items_shops_stocks', function (Blueprint $table) {
            $table->bigInteger('item_id')->unsigned()->comment('商品ID');
            $table->bigInteger('shop_id')->unsigned()->comment('店舗ID');
            $table->bigInteger('quantity')->unsigned()->comment('在庫数');
            $table->timestamps();
            $table->primary(['item_id', 'shop_id']);

            $table->foreign('item_id')
               ->references('id')->on('items')
               ->onUpdate('cascade')
               ->onDelete('cascade');
            $table->foreign('shop_id')
               ->references('id')->on('shops')
               ->onUpdate('cascade')
               ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_users');
        Schema::dropIfExists('desired_times');
        Schema::dropIfExists('makers');
        Schema::dropIfExists('regions');
        Schema::dropIfExists('prefectures');
        Schema::dropIfExists('shops');
        Schema::dropIfExists('item_categories');
        Schema::dropIfExists('item_category_closure');
        Schema::dropIfExists('items');
        Schema::dropIfExists('item_categories_items');
        Schema::dropIfExists('files');
        Schema::dropIfExists('items_files');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_details');
        Schema::dropIfExists('admin_users_shops');
        Schema::dropIfExists('items_shops_stocks');

        $tableNames = config('permission.table_names');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not found and defaults could not be merged. Please publish the package configuration before proceeding, or drop the tables manually.');
        }

        Schema::drop($tableNames['role_has_permissions']);
        Schema::drop($tableNames['model_has_roles']);
        Schema::drop($tableNames['model_has_permissions']);
        Schema::drop($tableNames['roles']);
        Schema::drop($tableNames['permissions']);
    }
}
