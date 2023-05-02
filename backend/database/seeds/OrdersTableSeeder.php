<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\Entities\Order;
use App\Models\Entities\OrderDetail;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($count = 1;$count <= 10000;$count ++){
            $order = factory(Order::class)->create();
            $order_detail = factory(OrderDetail::class)->create([
                'order_id' => $order->id,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at,
            ]);
        }
    }
}
