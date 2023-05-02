<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class WebAdminTest extends BaseTestCase
{
    public function testGet()
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/admin/login');
    }

    public function testAdminGet()
    {
        $response = $this->testAdminLogin();
        $response = $response->get('/admin');
        $response->assertStatus(200);
    }

    public function testManagerGet()
    {
        $response = $this->testManagerLogin();
        $response = $response->get('/admin');
        $response->assertStatus(200);
    }

    public function testShopGet()
    {
        $response = $this->testShopLogin();
        $response = $response->get('/admin');
        $response->assertStatus(302);
    }

}
