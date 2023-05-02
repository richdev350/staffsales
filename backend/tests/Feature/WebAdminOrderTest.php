<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Entities\Order;
use App\Models\Entities\OrderDetail;

class WebAdminOrderTest extends BaseTestCase
{
    public function testPrepareDatabase(){
        $orders = factory(Order::class, 5)->create();
        foreach($orders as $order){
            $order_detail = factory(OrderDetail::class)->create(['order_id' => $order->id]);
        }
        $this->assertTrue(true);
    }

    public function testGetOrderList()
    {
        $response = $this->get('/admin/order/list');
        $response->assertStatus(302);
    }

    public function testAdminGetOrderList()
    {
        $response = $this->testAdminLogin();
        $response = $response->get('/admin/order/list');
        $response->assertStatus(200);
    }

    public function testManagerGetOrderList()
    {
        $response = $this->testManagerLogin();
        $response = $response->get('/admin/order/list');
        $response->assertStatus(200);
    }

    public function testAdminGetOrderCsv()
    {
        $response = $this->testAdminLogin();
        $response = $response->get('/admin/order/export');
        $response->assertStatus(200);
    }

    public function testManagerGetOrderCsv()
    {
        $response = $this->testManagerLogin();
        $response = $response->get('/admin/order/export');
        $response->assertStatus(302);
    }

    public function testShopGetOrderList()
    {
        $response = $this->testShopLogin();
        $response = $response->get('/admin/order/list');
        $response->assertStatus(302);
    }

    public function testAdminPostOrder()
    {
        $response = $this->testAdminLogin();
        $response = $response->post('/admin/order');
        $response->assertRedirect('/admin/order');
    }

    public function testAdminGetOrderShow()
    {
        $id = 1;
        $order = Order::find($id);
        $this->assertNotNull($order);

        $response = $this->testAdminLogin();
        $response = $response->get('/admin/order/show/'.$id);
        $response->assertStatus(200);
    }

    public function testAdminGetOrderShowError()
    {
        $id = 9999;
        $order = Order::find($id);
        $this->assertNull($order);

        $response = $this->testAdminLogin();
        $response = $response->get('/admin/order/show/'.$id);
        $response->assertStatus(404);
    }

    public function testAdminGetOrderEdit()
    {
        $id = 1;
        $order = Order::find($id);
        $this->assertNotNull($order);

        $response = $this->testAdminLogin();
        $response = $response->get('/admin/order/edit/'.$id);
        $response->assertStatus(200);
    }

    public function testAdminGetOrderEditError()
    {
        $id = 9999;
        $order = Order::find($id);
        $this->assertNull($order);

        $response = $this->testAdminLogin();
        $response = $response->get('/admin/order/edit/'.$id);
        $response->assertStatus(404);
    }

    public function testAdminPutOrderEditWithConfirm()
    {
        $id = 4;
        $order = Order::find($id);
        $this->assertNotNull($order);

        $response = $this->testAdminLogin();
        $param = [
            'name'=>'高橋',
            'tel'=>'0902350000',
            'desired_date'=>'2020-12-22',
            'desired_time_id'=>2,
            'action'=>'confirm',
        ];
        $response = $response->put('/admin/order/edit/'.$id, $param);
        $response->assertStatus(200);
    }

    public function testAdminPutOrderEditWithSave()
    {
        $id = 4;
        $order = Order::find($id);
        $this->assertNotNull($order);

        $response = $this->testAdminLogin();
        $param = [
            'name'=>'高橋',
            'tel'=>'0902350000',
            'desired_date'=>'2020-12-22',
            'desired_time_id'=>2,
            'action'=>'save',
        ];
        $response = $response->put('/admin/order/edit/'.$id, $param);
        $response->assertRedirect('/admin/order');
    }

    public function testAdminPutOrderEditWithReturn()
    {
        $id = 4;
        $order = Order::find($id);
        $this->assertNotNull($order);

        $response = $this->testAdminLogin();
        $param = [
            'name'=>'高橋',
            'tel'=>'0902350000',
            'desired_date'=>'2020-12-22',
            'desired_time_id'=>2,
            'action'=>'return',
        ];
        $response = $response->put('/admin/order/edit/'.$id, $param);
        $response->assertStatus(200);
    }

    public function testAdminDeleteOrderDestroy()
    {
        $id = 5;
        $order = Order::find($id);
        $this->assertNotNull($order);

        $response = $this->testAdminLogin();
        $response = $response->delete('/admin/order/destroy/'.$id);
        $response->assertRedirect('/admin/order');
    }

    public function testAdminDeleteOrderDestroyError()
    {
        $id = 9999;
        $order = Order::find($id);
        $this->assertNull($order);

        $response = $this->testAdminLogin();
        $response = $response->delete('/admin/order/destroy/'.$id);
        $response->assertStatus(404);
    }

    public function testManagerGetOrderShow()
    {
        $id = 1;
        $order = Order::find($id);
        $this->assertNotNull($order);

        $response = $this->testManagerLogin();
        $response = $response->get('/admin/order/show/'.$id);
        $response->assertStatus(200);
    }

    public function testManagerGetOrderEdit()
    {
        $id = 1;
        $order = Order::find($id);
        $this->assertNotNull($order);

        $response = $this->testManagerLogin();
        $response = $response->get('/admin/order/edit/'.$id);
        $response->assertStatus(302);
    }

    public function testManagerPutOrderEditWithConfirm()
    {
        $id = 4;
        $order = Order::find($id);
        $this->assertNotNull($order);

        $response = $this->testManagerLogin();
        $param = [
            'name'=>'高橋',
            'tel'=>'0902350000',
            'desired_date'=>'2020-12-22',
            'desired_time_id'=>2,
            'action'=>'confirm',
        ];
        $response = $response->put('/admin/order/edit/'.$id, $param);
        $response->assertStatus(302);
    }

    public function testManagerPutOrderEditWithSave()
    {
        $id = 4;
        $order = Order::find($id);
        $this->assertNotNull($order);

        $response = $this->testManagerLogin();
        $param = [
            'name'=>'高橋',
            'tel'=>'0902350000',
            'desired_date'=>'2020-12-22',
            'desired_time_id'=>2,
            'action'=>'save',
        ];
        $response = $response->put('/admin/order/edit/'.$id, $param);
        $response->assertStatus(302);
    }

    public function testManagerPutOrderEditWithReturn()
    {
        $id = 4;
        $order = Order::find($id);
        $this->assertNotNull($order);

        $response = $this->testManagerLogin();
        $param = [
            'name'=>'高橋',
            'tel'=>'0902350000',
            'desired_date'=>'2020-12-22',
            'desired_time_id'=>2,
            'action'=>'return',
        ];
        $response = $response->put('/admin/order/edit/'.$id, $param);
        $response->assertStatus(302);
    }

    public function testManagerDeleteOrderDestroy()
    {
        $id = 1;
        $order = Order::find($id);
        $this->assertNotNull($order);

        $response = $this->testManagerLogin();
        $response = $response->delete('/admin/order/destroy/'.$id);
        $response->assertStatus(302);
    }

}
