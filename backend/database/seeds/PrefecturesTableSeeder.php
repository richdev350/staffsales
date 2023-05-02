<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PrefecturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('prefectures')->truncate();
        DB::table('prefectures')->insert([
            ['region_id' => 1, 'code' => 1, 'name' => '北海道','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 2, 'code' => 2, 'name' => '青森県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 2, 'code' => 3, 'name' => '岩手県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 2, 'code' => 4, 'name' => '宮城県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 2, 'code' => 5, 'name' => '秋田県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 2, 'code' => 6, 'name' => '山形県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 2, 'code' => 7, 'name' => '福島県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 3, 'code' => 8, 'name' => '茨城県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 3, 'code' => 9, 'name' => '栃木県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 3, 'code' => 10, 'name' => '群馬県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 3, 'code' => 11, 'name' => '埼玉県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 3, 'code' => 12, 'name' => '千葉県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 3, 'code' => 13, 'name' => '東京都','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 3, 'code' => 14, 'name' => '神奈川県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 4, 'code' => 15, 'name' => '新潟県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 4, 'code' => 16, 'name' => '富山県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 4, 'code' => 17, 'name' => '石川県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 4, 'code' => 18, 'name' => '福井県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 4, 'code' => 19, 'name' => '山梨県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 4, 'code' => 20, 'name' => '長野県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 4, 'code' => 21, 'name' => '岐阜県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 4, 'code' => 22, 'name' => '静岡県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 4, 'code' => 23, 'name' => '愛知県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 5, 'code' => 24, 'name' => '三重県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 5, 'code' => 25, 'name' => '滋賀県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 5, 'code' => 26, 'name' => '京都府','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 5, 'code' => 27, 'name' => '大阪府','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 5, 'code' => 28, 'name' => '兵庫県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 5, 'code' => 29, 'name' => '奈良県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 5, 'code' => 30, 'name' => '和歌山県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 6, 'code' => 31, 'name' => '鳥取県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 6, 'code' => 32, 'name' => '島根県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 6, 'code' => 33, 'name' => '岡山県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 6, 'code' => 34, 'name' => '広島県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 6, 'code' => 35, 'name' => '山口県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 7, 'code' => 36, 'name' => '徳島県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 7, 'code' => 37, 'name' => '香川県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 7, 'code' => 38, 'name' => '愛媛県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 7, 'code' => 39, 'name' => '高知県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 8, 'code' => 40, 'name' => '福岡県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 8, 'code' => 41, 'name' => '佐賀県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 8, 'code' => 42, 'name' => '長崎県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 8, 'code' => 43, 'name' => '熊本県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 8, 'code' => 44, 'name' => '大分県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 8, 'code' => 45, 'name' => '宮崎県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 8, 'code' => 46, 'name' => '鹿児島県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['region_id' => 8, 'code' => 47, 'name' => '沖縄県','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
        ]);
    }
}
