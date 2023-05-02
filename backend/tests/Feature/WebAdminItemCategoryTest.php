<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Entities\ItemCategory;

class WebAdminItemCategoryTest extends BaseTestCase
{
    public function testGetItemCategoryList()
    {
        $response = $this->get('/admin/item-category/list');
        $response->assertStatus(302);
    }

    public function testAdminGetItemCategoryList()
    {
        $response = $this->testAdminLogin();
        $response = $response->get('/admin/item-category/list');
        $response->assertStatus(200);
    }

    public function testManagerGetItemCategoryList()
    {
        $response = $this->testManagerLogin();
        $response = $response->get('/admin/item-category/list');
        $response->assertStatus(302);
    }

    public function testShopGetItemCategoryList()
    {
        $response = $this->testShopLogin();
        $response = $response->get('/admin/item-category/list');
        $response->assertStatus(302);
    }

}
