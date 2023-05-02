<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Entities\Order;
use App\Models\Entities\Shop;
use App\Models\Entities\DesiredTime;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    $random_datetime = $faker->dateTimeBetween('-1 years');
    $shop = Shop::inRandomOrder()->first();
    $desired_time = DesiredTime::inRandomOrder()->first();
    $tel = $faker->phoneNumber();
    $tel = str_replace('-', '', $tel);
    // '2020-12-26'未満の日付がセットされる
    $desired_date = $faker->dateTimeBetween('2020-12-21', '2020-12-26')->format('Y-m-d');
    return [
        'shop_id' => $shop->id,
        'name' => $faker->name,
        'tel' => $tel,
        'sum' => rand(1000, 10000),
        'delivery_fee' => 0,
        'desired_date' => $desired_date,
        'desired_time_id' => $desired_time->id,
        'state' => 'pending',
        'secure_code' => $faker->unique->ean8,
        'created_at' => $random_datetime,
        'updated_at' => $random_datetime,
    ];
});
