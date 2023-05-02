<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class WebAdminLogoutTest extends BaseTestCase
{
    public function testGet()
    {
        $response = $this->get('/admin/logout');
        $response->assertRedirect('/admin/login');
    }
}
