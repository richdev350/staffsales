<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Entities\Item;

class WebApiCartTest extends BaseTestCase
{

    public function testPostCartChange()
    {
        $item_id = 1;
        $item = Item::find($item_id);
        $this->assertNotNull($item);
        $param = [
            'item_id' => $item_id,
            'amount' => 2,
        ];
        $response = $this->post('/api/cart/change', $param, [
            'HTTP_REFERER' =>  url('/'),
        ]);
        $response->assertStatus(200);
    }

    public function testAdminPostCartChange()
    {
        $response = $this->testAdminLogin();
        $item_id = 1;
        $item = Item::find($item_id);
        $this->assertNotNull($item);
        $param = [
            'item_id' => $item_id,
            'amount' => 2,
        ];
        $response = $this->post('/api/cart/change', $param, [
            'HTTP_REFERER' =>  url('/'),
        ]);
        $response->assertStatus(200);
    }

    public function testManagerPostCartChange()
    {
        $response = $this->testManagerLogin();
        $item_id = 1;
        $item = Item::find($item_id);
        $this->assertNotNull($item);
        $param = [
            'item_id' => $item_id,
            'amount' => 2,
        ];
        $response = $this->post('/api/cart/change', $param, [
            'HTTP_REFERER' =>  url('/'),
        ]);
        $response->assertStatus(200);
    }

    public function testShopPostCartChange()
    {
        $response = $this->testShopLogin();
        $item_id = 1;
        $item = Item::find($item_id);
        $this->assertNotNull($item);
        $param = [
            'item_id' => $item_id,
            'amount' => 2,
        ];
        $response = $this->post('/api/cart/change', $param, [
            'HTTP_REFERER' =>  url('/'),
        ]);
        $response->assertStatus(200);
    }

    public function testDeleteCartDelete()
    {
        $item_id = 1;
        $item = Item::find($item_id);
        $this->assertNotNull($item);
        $param = [
            'item_id' => $item_id,
        ];
        $response = $this->delete('/api/cart/delete', $param, [
            'HTTP_REFERER' =>  url('/'),
        ]);
        $response->assertStatus(200);
    }

}
