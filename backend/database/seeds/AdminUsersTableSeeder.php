<?php

use database\seeds\CarbonOnlyCsvSeeder\CarbonOnlyCsvSeeder;
setlocale(LC_ALL, 'ja_JP.UTF-8');

class AdminUsersTableSeeder extends CarbonOnlyCsvSeeder
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
      $this->table = 'admin_users';
      $this->filename = base_path().'/database/seeds/csv/admin_users.csv';
      $this->hashable = [];
    }

    public function run()
    {
      DB::disableQueryLog();
      DB::table('admin_users')->truncate();
      parent::run();
    }
}
