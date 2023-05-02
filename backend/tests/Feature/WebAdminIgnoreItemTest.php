<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class WebAdminIgnoreItemTest extends BaseTestCase
{

    public function testGetIgnoreItemList()
    {
        $response = $this->get('/admin/ignore-item/1');
        $response->assertStatus(302);
    }

    public function testAdminGetIgnoreItemList()
    {
        $response = $this->testAdminLogin();
        $response = $this->get('/admin/ignore-item/1');
        $response->assertStatus(200);
    }

    public function testManagerGetIgnoreItemList()
    {
        $response = $this->testManagerLogin();
        $response = $this->get('/admin/ignore-item/1');
        $response->assertStatus(302);
    }

    public function testShopGetIgnoreItemList()
    {
        $response = $this->testShopLogin();
        $response = $this->get('/admin/ignore-item/1');
        $response->assertStatus(302);
    }

    public function testPostIgnoreItemBatch()
    {
        $param = [
            'targets' => '1,2,3,4,5',
            'action' => 'ignore',
        ];
        $response = $this->post('/admin/ignore-item/batch/1', $param);
        $response->assertStatus(302);
    }

    public function testAdminPostIgnoreItemBatch()
    {
        $response = $this->testAdminLogin();
        $param = [
            'targets' => '1,2,3,4,5',
            'action' => 'ignore',
        ];
        $response = $this->post('/admin/ignore-item/batch/1', $param);
        $response->assertStatus(302)->assertRedirect('/admin/ignore-item/1');
    }

    public function testManagerPostIgnoreItemBatch()
    {
        $param = [
            'targets' => '1,2,3,4,5',
            'action' => 'ignore',
        ];
        $response = $this->testManagerLogin();
        $response = $this->post('/admin/ignore-item/batch/1', $param);
        $response->assertStatus(302);
    }

    public function testShopPostIgnoreItemBatch()
    {
        $param = [
            'targets' => '1,2,3,4,5',
            'action' => 'ignore',
        ];
        $response = $this->testShopLogin();
        $response = $this->post('/admin/ignore-item/batch/1', $param);
        $response->assertStatus(302);
    }

}
