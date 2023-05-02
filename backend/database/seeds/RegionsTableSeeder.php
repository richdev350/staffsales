<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('regions')->truncate();
        DB::table('regions')->insert([
            ['name' => '北海道地方','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['name' => '東北地方','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['name' => '関東地方','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['name' => '中部地方','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['name' => '近畿地方','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['name' => '中国地方','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['name' => '四国地方','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['name' => '九州地方','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
        ]);
    }
}
