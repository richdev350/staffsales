<?php

use database\seeds\CarbonOnlyCsvSeeder\CarbonOnlyCsvSeeder;
setlocale(LC_ALL, 'ja_JP.UTF-8');

class ItemCategoriesItemsTableSeeder extends CarbonOnlyCsvSeeder
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
      $this->table = 'item_categories_items';
      $this->filename = base_path().'/database/seeds/csv/item_categories_items.csv';
    }

    public function run()
    {
      DB::disableQueryLog();
      DB::table('item_categories_items')->truncate();
      parent::run();
    }
}
