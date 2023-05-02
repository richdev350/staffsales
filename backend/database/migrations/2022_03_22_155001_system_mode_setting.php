<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SystemModeSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_mode_setting', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->datetime('exhibit_date')->nullable()->comment('公開開始日時');
            $table->datetime('sales_start_date')->nullable()->comment('販売開始日時');
            $table->datetime('end_of_sale_date')->nullable()->comment('終了日時');
            $table->boolean('is_end_of_sale_date_visible')->unsigned()->default(1)->comment('表示・非表示 0:非表示、1:表示');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_mode_setting');
    }
}
