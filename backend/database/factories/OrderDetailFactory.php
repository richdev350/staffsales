<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Entities\OrderDetail;
use App\Models\Entities\Item;
use Faker\Generator as Faker;

$factory->define(OrderDetail::class, function (Faker $faker) {
    $item = Item::inRandomOrder()->first();
    return [
        'order_id' => 1,
        'item_id' => $item->id,
        'price' => $item->price,
        'amount' => $faker->numberBetween(1,5),
        'created_at' => now(),
        'updated_at' => now(),
    ];
});
