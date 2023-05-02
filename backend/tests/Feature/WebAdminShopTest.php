<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Entities\Shop;

class WebAdminShopTest extends BaseTestCase
{

    public function testGetShopList()
    {
        $response = $this->get('/admin/shop/list');
        $response->assertStatus(302);
    }

    public function testAdminGetShopList()
    {
        $response = $this->testAdminLogin();
        $response = $response->get('/admin/shop/list');
        $response->assertStatus(200);
    }

    public function testManagerGetShopList()
    {
        $response = $this->testManagerLogin();
        $response = $response->get('/admin/shop/list');
        $response->assertStatus(302);
    }

    public function testShopGetShopList()
    {
        $response = $this->testShopLogin();
        $response = $response->get('/admin/shop/list');
        $response->assertStatus(302);
    }

    public function testAdminPostShop()
    {
        $response = $this->testAdminLogin();
        $response = $response->post('/admin/shop');
        $response->assertRedirect('/admin/shop');
    }

    public function testAdminGetShopCreate()
    {
        $response = $this->testAdminLogin();
        $response = $response->get('/admin/shop/create');
        $response->assertStatus(200);
    }

    public function testAdminPostShopCreateWithConfirm()
    {
        $response = $this->testAdminLogin();
        $param = [
            'code' => '88888',
            'name' => 'テスト店 ',
            'zip_code' => 8900108,
            'prefecture_id' => 46,
            'city' => '鹿児島市',
            'address' => 'ああ5丁目24-1',
            'tel' => '0990000000',
            'manager_id' => 2,
            'staff_id' => 3,
            'action' => 'confirm',
        ];
        $response = $response->post('/admin/shop/create', $param);
        $response->assertStatus(200);
    }

    public function testAdminPostShopCreateWithSave()
    {
        $response = $this->testAdminLogin();
        $param = [
            'code' => '88888',
            'name' => 'テスト店 ',
            'zip_code' => 8900108,
            'prefecture_id' => 46,
            'city' => '鹿児島市',
            'address' => 'ああ5丁目24-1',
            'tel' => '0990000000',
            'manager_id' => 2,
            'staff_id' => 3,
            'action' => 'save',
        ];
        $response = $response->post('/admin/shop/create', $param);
        $response->assertRedirect('/admin/shop');
    }

    public function testAdminPostShopCreateWithReturn()
    {
        $response = $this->testAdminLogin();
        $param = [
            'code' => '88888',
            'name' => 'テスト店 ',
            'zip_code' => 8900108,
            'prefecture_id' => 46,
            'city' => '鹿児島市',
            'address' => 'ああ5丁目24-1',
            'tel' => '0990000000',
            'manager_id' => 2,
            'staff_id' => 3,
            'action' => 'return',
        ];
        $response = $response->post('/admin/shop/create', $param);
        $response->assertStatus(200);
    }

    public function testAdminGetShopShow()
    {
        $id = 1;
        $shop = Shop::find($id);
        $this->assertNotNull($shop);

        $response = $this->testAdminLogin();
        $response = $response->get('/admin/shop/show/'.$id);
        $response->assertStatus(200);
    }

    public function testAdminGetShopShowError()
    {
        $id = 9999;
        $shop = Shop::find($id);
        $this->assertNull($shop);

        $response = $this->testAdminLogin();
        $response = $response->get('/admin/shop/show/'.$id);
        $response->assertStatus(404);
    }

    public function testAdminGetShopEdit()
    {
        $id = 1;
        $shop = Shop::find($id);
        $this->assertNotNull($shop);

        $response = $this->testAdminLogin();
        $response = $response->get('/admin/shop/edit/'.$id);
        $response->assertStatus(200);
    }

    public function testAdminGetShopEditError()
    {
        $id = 9999;
        $shop = Shop::find($id);
        $this->assertNull($shop);

        $response = $this->testAdminLogin();
        $response = $response->get('/admin/shop/edit/'.$id);
        $response->assertStatus(404);
    }

    public function testAdminPutShopEditWithConfirm()
    {
        $id = 4;
        $shop = Shop::find($id);
        $this->assertNotNull($shop);

        $response = $this->testAdminLogin();
        $param = [
            'code' => '88889',
            'name' => 'テスト店 ',
            'zip_code' => 8900108,
            'prefecture_id' => 46,
            'city' => '鹿児島市',
            'address' => 'ああ5丁目24-1',
            'tel' => '0990000000',
            'manager_id' => 2,
            'staff_id' => 3,
            'action' => 'confirm',
        ];
        $response = $response->put('/admin/shop/edit/'.$id, $param);
        $response->assertStatus(200);
    }

    public function testAdminPutShopEditWithSave()
    {
        $id = 4;
        $shop = Shop::find($id);
        $this->assertNotNull($shop);

        $response = $this->testAdminLogin();
        $param = [
            'code' => '88889',
            'name' => 'テスト店 ',
            'zip_code' => 8900108,
            'prefecture_id' => 46,
            'city' => '鹿児島市',
            'address' => 'ああ5丁目24-1',
            'tel' => '0990000000',
            'manager_id' => 2,
            'staff_id' => 3,
            'action' => 'save',
        ];
        $response = $response->put('/admin/shop/edit/'.$id, $param);
        $response->assertRedirect('/admin/shop');
    }

    public function testAdminPutShopEditWithReturn()
    {
        $id = 4;
        $shop = Shop::find($id);
        $this->assertNotNull($shop);

        $response = $this->testAdminLogin();
        $param = [
            'code' => '88889',
            'name' => 'テスト店 ',
            'zip_code' => 8900108,
            'prefecture_id' => 46,
            'city' => '鹿児島市',
            'address' => 'ああ5丁目24-1',
            'tel' => '0990000000',
            'manager_id' => 2,
            'staff_id' => 3,
            'action' => 'return',
        ];
        $response = $response->put('/admin/shop/edit/'.$id, $param);
        $response->assertStatus(200);
    }

    public function testAdminDeleteShopDestroy()
    {
        $id = 5;
        $shop = Shop::find($id);
        $this->assertNotNull($shop);

        $response = $this->testAdminLogin();
        $response = $response->delete('/admin/shop/destroy/'.$id);
        $response->assertRedirect('/admin/shop');
    }

    public function testAdminDeleteShopDestroyError()
    {
        $id = 9999;
        $shop = Shop::find($id);
        $this->assertNull($shop);

        $response = $this->testAdminLogin();
        $response = $response->delete('/admin/shop/destroy/'.$id);
        $response->assertStatus(404);
    }

}
