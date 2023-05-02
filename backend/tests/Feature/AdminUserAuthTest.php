<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Services\Admin\AuthenticateAdminUserService;
use App\Models\Entities\AdminUser;

class AdminUserAuthTest extends TestCase
{
    public function testLoginView()
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
        $this->assertFalse(AuthenticateAdminUserService::isAuthenticated());
    }

    public function testNonLoginAccess()
    {
        $response = $this->get('/admin');
        $response->assertStatus(302)
                 ->assertRedirect('/admin/login');
        $this->assertFalse(AuthenticateAdminUserService::isAuthenticated());
    }

    public function testLogin()
    {
        $this->assertFalse(AuthenticateAdminUserService::isAuthenticated());
        $response = $this->dummyLogin();
        $response->assertStatus(200);
        $this->assertTrue(AuthenticateAdminUserService::isAuthenticated());
    }

    public function testLogout()
    {
        $response = $this->dummyLogin();
        $this->assertTrue(AuthenticateAdminUserService::isAuthenticated());
        $response = $this->post('/admin/logout');
        $response->assertStatus(302)
                 ->assertRedirect('/admin/login');
        $this->assertFalse(AuthenticateAdminUserService::isAuthenticated());
    }

    private function dummyLogin()
    {
        $user = AdminUser::find(1);
        $this->assertNotNull($user);
        return $this->actingAs($user)
                    ->withSession(['admin_user' => $user])
                    ->get('/admin');
    }
}
