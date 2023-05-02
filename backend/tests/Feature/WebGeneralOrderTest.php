<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Controllers\Api\CartController;
use App\Models\Entities\Item;
use App\Models\Entities\Order;

class WebGeneralOrderTest extends BaseTestCase
{
    protected $preserveGlobalState = FALSE;
    protected $runTestInSeparateProcess = TRUE;

    public function testGetOrderForm()
    {
        $response = $this->get('/order/form');
        $response->assertStatus(302);
    }

    public function testAdminGetOrderForm()
    {
        $response = $this->testAdminLogin();
        $response = $this->get('/order/form');
        $response->assertStatus(302);
    }

    public function testManagerGetOrderForm()
    {
        $response = $this->testManagerLogin();
        $response = $this->get('/order/form');
        $response->assertStatus(302);
    }

    public function testShopGetOrderForm()
    {
        $response = $this->testShopLogin();
        $response = $this->get('/order/form');
        $response->assertRedirect('/home');
    }

    public function testShopGetOrderFormWithCartSession()
    {
        $response = $this->testShopLogin();
        $item_id = 1;
        $amount = 2;
        $item = Item::find($item_id);
        $this->assertNotNull($item);
        $response = $this->withSession([CartController::SESSION_KEY_SHOP_CART => [$item_id => $amount]])->get('/order/form');
        $response->assertStatus(200);
    }

    public function testShopPostOrderConfirmWithConfirm()
    {
        $response = $this->testShopLogin();
        $param = [
            'name' => '太郎',
            'tel' => '08055555555',
            'desired_date' => '2020-12-22',
            'desired_time_id' => 1,
            'action' => 'confirm',
        ];
        $response = $this->post('/order/confirm', $param);
        $response->assertRedirect('/home');
    }

    public function testShopPostOrderConfirmWithConfirmWithCartSession()
    {
        $response = $this->testShopLogin();
        $param = [
            'name' => '太郎',
            'tel' => '08055555555',
            'desired_date' => '2020-12-22',
            'desired_time_id' => 1,
            'action' => 'confirm',
        ];
        $item_id = 1;
        $amount = 2;
        $item = Item::find($item_id);
        $this->assertNotNull($item);
        $response = $this->withSession([CartController::SESSION_KEY_SHOP_CART => [$item_id => $amount]])->post('/order/confirm', $param);
        $response->assertStatus(200);
    }

    public function testShopPostOrderConfirmWithSave()
    {
        $response = $this->testShopLogin();
        $param = [
            'name' => '太郎',
            'tel' => '08055555555',
            'desired_date' => '2020-12-22',
            'desired_time_id' => 1,
            'action' => 'save',
        ];
        $response = $this->post('/order/confirm', $param);
        $response->assertRedirect('/home');
    }

    public function testShopPostOrderConfirmWithSaveWithCartSession()
    {
        $response = $this->testShopLogin();
        $param = [
            'name' => '太郎',
            'tel' => '08055555555',
            'desired_date' => '2020-12-22',
            'desired_time_id' => 1,
            'action' => 'save',
        ];
        $item_id = 1;
        $amount = 2;
        $item = Item::find($item_id);
        $this->assertNotNull($item);
        $response = $this->withSession([CartController::SESSION_KEY_SHOP_CART => [$item_id => $amount]])->post('/order/confirm', $param);
        $response->assertRedirect('/order/thanks');
    }

    public function testShopPostOrderConfirmWithReturn()
    {
        $response = $this->testShopLogin();
        $param = [
            'name' => '太郎',
            'tel' => '08055555555',
            'desired_date' => '2020-12-22',
            'desired_time_id' => 1,
            'action' => 'return',
        ];
        $response = $this->post('/order/confirm', $param);
        $response->assertRedirect('/home');
    }

    public function testShopPostOrderConfirmWithReturnWithCartSession()
    {
        $response = $this->testShopLogin();
        $param = [
            'name' => '太郎',
            'tel' => '08055555555',
            'desired_date' => '2020-12-22',
            'desired_time_id' => 1,
            'action' => 'return',
        ];
        $item_id = 1;
        $amount = 2;
        $item = Item::find($item_id);
        $this->assertNotNull($item);
        $response = $this->withSession([CartController::SESSION_KEY_SHOP_CART => [$item_id => $amount]])->post('/order/confirm', $param);
        $response->assertStatus(200);
    }

    public function testShopGetOrderThanks()
    {
        $response = $this->testShopLogin();
        $response = $this->get('/order/thanks');
        $response->assertRedirect('/item/list?item_category_id=1');
    }

    public function testShopGetOrderThanksWithOrderSession()
    {
        $response = $this->testShopLogin();
        $order_id = 1;
        $order = Order::find($order_id);
        $this->assertNotNull($order);
        $response = $this->withSession(['shop_order_id' => $order_id])->get('/order/thanks');
        $response->assertStatus(200);
    }
}
