<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class WebAdminLoginTest extends BaseTestCase
{
    public function testGet()
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
    }

    public function testPostLoginValidationError()
    {
        $response = $this->post('/admin/login', ['login_id'=>'', 'password'=>'']);
        $response->assertStatus(200);
    }

    public function testAdminPostLogin()
    {
        $response = $this->post('/admin/login', ['login_id'=>'99999', 'password'=>'y2design']);
        $response->assertRedirect('/admin');
    }

    public function testManagerPostLogin()
    {
        $response = $this->from('/admin/login')
            ->post('/admin/login', ['login_id'=>'10016m', 'password'=>'y2design']);
            $response->assertRedirect('/admin/order');
    }

    public function testGeneralPostLogin()
    {
        $response = $this->from('/admin/login')
            ->post('/admin/login', ['login_id'=>'10016', 'password'=>'y2design']);
            $response->assertRedirect('/home');
    }
}
