<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Entities\ItemCategory;

class WebApiItemCategoryTest extends BaseTestCase
{

    public function testGetItemCategoryList()
    {
        $response = $this->get('/api/item-category/list', [
            'HTTP_REFERER' =>  url('/'),
        ]);
        $response->assertStatus(200);
    }

    public function testAdminGetItemCategoryList()
    {
        $response = $this->testAdminLogin();
        $response = $this->get('/api/item-category/list', [
            'HTTP_REFERER' =>  url('/'),
        ]);
        $response->assertStatus(200);
    }

    public function testManagerGetItemCategoryList()
    {
        $response = $this->testManagerLogin();
        $response = $this->get('/api/item-category/list', [
            'HTTP_REFERER' =>  url('/'),
        ]);
        $response->assertStatus(200);
    }

    public function testShopGetItemCategoryList()
    {
        $response = $this->testShopLogin();
        $response = $this->get('/api/item-category/list', [
            'HTTP_REFERER' =>  url('/'),
        ]);
        $response->assertStatus(200);
    }

    public function testPostItemCategoryCreate()
    {
        $response = $this->post('/api/item-category/create', [], [
            'HTTP_REFERER' =>  url('/'),
        ]);
        $response->assertStatus(200);
    }

    public function testPutItemCategoryEdit()
    {
        $item_category_id = 1;
        $item_category = ItemCategory::find($item_category_id);
        $this->assertNotNull($item_category);
        $param = [
            'id' => $item_category_id,
            'name' => 'テスト',
        ];
        $response = $this->put('/api/item-category/edit', $param, [
            'HTTP_REFERER' =>  url('/'),
        ]);
        $response->assertStatus(200);
    }

    public function testDeleteItemCategoryDestroy()
    {
        $item_category_id = 1;
        $item_category = ItemCategory::find($item_category_id);
        $this->assertNotNull($item_category);
        $param = [
            'id' => $item_category_id,
        ];
        $response = $this->delete('/api/item-category/destroy', $param, [
            'HTTP_REFERER' =>  url('/'),
        ]);
        $response->assertStatus(200);
    }

}
