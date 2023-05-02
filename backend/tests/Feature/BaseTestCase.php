<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Entities\AdminUser;

class BaseTestCase extends TestCase
{

    public function testAdminLogin()
    {
        $user = AdminUser::where('login_id', '99999')->first();
        $this->assertNotNull($user);
        return $this->actingAs($user)->withSession(['admin_user' => $user]);
    }

    public function testManagerLogin()
    {
        $user = AdminUser::where('login_id', '10016m')->first();
        $this->assertNotNull($user);
        return $this->actingAs($user)->withSession(['admin_user' => $user]);
    }

    public function testShopLogin()
    {
        $user = AdminUser::where('login_id', '10016')->first();
        $this->assertNotNull($user);
        return $this->actingAs($user)->withSession(['admin_user' => $user]);
    }
}
