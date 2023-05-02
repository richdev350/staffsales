<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OrderDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        DB::table('orders')->truncate();
        DB::table('order_details')->insert([
            ['order_id' => 1,'item_id' => 1,'price' => 3500, 'amount' => 1, 'created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['order_id' => 1,'item_id' => 2,'price' => 3200, 'amount' => 2, 'created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['order_id' => 1,'item_id' => 3,'price' => 2800, 'amount' => 3, 'created_at' => Carbon::now(),'updated_at' => Carbon::now()],
        ]);
    }
}
