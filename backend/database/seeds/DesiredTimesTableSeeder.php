<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DesiredTimesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('desired_times')->truncate();
        DB::table('desired_times')->insert([
            ['from' => '10','to' => '13','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['from' => '13','to' => '17','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['from' => '17','to' => '21','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
        ]);
    }
}
