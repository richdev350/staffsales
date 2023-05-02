<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Entities\Cart;

class WebGeneralCartTest extends BaseTestCase
{
    protected $preserveGlobalState = FALSE;
    protected $runTestInSeparateProcess = TRUE;

    public function testGetCartList()
    {
        $response = $this->get('/cart/list');
        $response->assertStatus(302);
    }

    public function testAdminGetCartList()
    {
        $response = $this->testAdminLogin();
        $response = $this->get('/cart/list');
        $response->assertStatus(302);
    }

    public function testManagerGetCartList()
    {
        $response = $this->testManagerLogin();
        $response = $this->get('/cart/list');
        $response->assertStatus(302);
    }

    public function testShopGetCartList()
    {
        $response = $this->testShopLogin();
        $response = $this->get('/cart/list');
        $response->assertStatus(200);
    }

}
