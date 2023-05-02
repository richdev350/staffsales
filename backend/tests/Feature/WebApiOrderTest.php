<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Entities\Order;
use App\Models\Entities\OrderDetail;

class WebApiOrderTest extends BaseTestCase
{
    public function testPrepareDatabase(){
        $orders = factory(Order::class, 5)->create();
        foreach($orders as $order){
            $order_detail = factory(OrderDetail::class)->create(['order_id' => $order->id]);
        }
        $this->assertTrue(true);
    }

    public function testGetOrderShow()
    {
        list($remote_addr, $mask) = explode('/', config('app.accept_ip'));
        $GLOBALS['_SERVER']['REMOTE_ADDR'] = $remote_addr;

        $order = Order::all()->first();
        $this->assertNotNull($order);
        $param = [
            'OrderNo' => $order->id,
            'ReceiveStoreNo' => $order->shop->code,
            'SecureCode' => $order->secure_code,
        ];
        $header = [
            'REMOTE_ADDR' => config('app.accept_ip'),
        ];
        $response = $this->call('GET', '/api/order/show', $param, [], [], $header);
        $response->assertStatus(200);
    }

    public function testAdminGetOrderShow()
    {
        $response = $this->testAdminLogin();
        list($remote_addr, $mask) = explode('/', config('app.accept_ip'));
        $GLOBALS['_SERVER']['REMOTE_ADDR'] = $remote_addr;

        $order = Order::all()->first();
        $this->assertNotNull($order);
        $param = [
            'OrderNo' => $order->id,
            'ReceiveStoreNo' => $order->shop->code,
            'SecureCode' => $order->secure_code,
        ];
        $header = [
            'REMOTE_ADDR' => config('app.accept_ip'),
        ];
        $response = $this->call('GET', '/api/order/show', $param, [], [], $header);
        $response->assertStatus(200);
    }

    public function testManagerGetOrderShow()
    {
        $response = $this->testManagerLogin();
        list($remote_addr, $mask) = explode('/', config('app.accept_ip'));
        $GLOBALS['_SERVER']['REMOTE_ADDR'] = $remote_addr;

        $order = Order::all()->first();
        $this->assertNotNull($order);
        $param = [
            'OrderNo' => $order->id,
            'ReceiveStoreNo' => $order->shop->code,
            'SecureCode' => $order->secure_code,
        ];
        $header = [
            'REMOTE_ADDR' => config('app.accept_ip'),
        ];
        $response = $this->call('GET', '/api/order/show', $param, [], [], $header);
        $response->assertStatus(200);
    }

    public function testShopGetOrderShow()
    {
        $response = $this->testShopLogin();
        list($remote_addr, $mask) = explode('/', config('app.accept_ip'));
        $GLOBALS['_SERVER']['REMOTE_ADDR'] = $remote_addr;

        $order = Order::all()->first();
        $this->assertNotNull($order);
        $param = [
            'OrderNo' => $order->id,
            'ReceiveStoreNo' => $order->shop->code,
            'SecureCode' => $order->secure_code,
        ];
        $header = [
            'REMOTE_ADDR' => config('app.accept_ip'),
        ];
        $response = $this->call('GET', '/api/order/show', $param, [], [], $header);
        $response->assertStatus(200);
    }

    public function testGetOrderUpdate()
    {
        list($remote_addr, $mask) = explode('/', config('app.accept_ip'));
        $GLOBALS['_SERVER']['REMOTE_ADDR'] = $remote_addr;

        $order = Order::all()->first();
        $this->assertNotNull($order);
        $param = [
            'OrderNo' => $order->id,
            'ReceiveStoreNo' => $order->shop->code,
            'Status' => $order->state->code() + 1,
            'SecureCode' => $order->secure_code,
        ];
        $header = [
            'REMOTE_ADDR' => config('app.accept_ip'),
        ];
        $response = $this->call('GET', '/api/order/update', $param, [], [], $header);
        $response->assertStatus(200);
    }

}
