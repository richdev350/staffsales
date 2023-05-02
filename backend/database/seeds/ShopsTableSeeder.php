<?php

use database\seeds\CarbonOnlyCsvSeeder\CarbonOnlyCsvSeeder;
setlocale(LC_ALL, 'ja_JP.UTF-8');

class ShopsTableSeeder extends CarbonOnlyCsvSeeder
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
      $this->timestamps = TRUE;
      $this->table = 'shops';
      $this->filename = base_path().'/database/seeds/csv/shops.csv';
    }

    public function run()
    {
      DB::disableQueryLog();
      DB::table('shops')->truncate();
      parent::run();
    }
}
