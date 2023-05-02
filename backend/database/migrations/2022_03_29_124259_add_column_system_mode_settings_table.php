<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSystemModeSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_mode_settings', function($table) {
            $table->boolean('emergency_flag')->unsigned()->default(0)->after('is_end_of_sale_date_visible');
        });
    }

    public function down()
    {
        Schema::table('system_mode_settings', function($table) {
            $table->dropColumn('emergency_flag');
        });
    }
}
