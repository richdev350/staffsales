<?php

use database\seeds\CarbonOnlyCsvSeeder\CarbonOnlyCsvSeeder;
setlocale(LC_ALL, 'ja_JP.UTF-8');

class ModelHasRolesTableSeeder extends CarbonOnlyCsvSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function __construct()
    {
      $this->delimiter = ',';
      $this->empty = TRUE;
      $this->timestamps = FALSE;
      $this->table = 'model_has_roles';
      $this->filename = base_path().'/database/seeds/csv/model_has_roles.csv';
    }

    public function run()
    {
      DB::disableQueryLog();
      DB::table('model_has_roles')->truncate();
      parent::run();
    }
}
