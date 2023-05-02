<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Entities\AdminUser;

class WebAdminAdminUserTest extends BaseTestCase
{
    use RefreshDatabase;

    public function testSeeding(){
        $this->seed();
        $this->assertTrue(true);
    }

    public function testGetAdminUserList()
    {
        $response = $this->get('/admin/admin-user/list');
        $response->assertStatus(302);
    }

    public function testAdminGetAdminUserList()
    {
        $response = $this->testAdminLogin();
        $response = $response->get('/admin/admin-user/list');
        $response->assertStatus(200);
    }

    public function testManagerGetAdminUserList()
    {
        $response = $this->testManagerLogin();
        $response = $response->get('/admin/admin-user/list');
        $response->assertStatus(302);
    }

    public function testShopGetAdminUserList()
    {
        $response = $this->testShopLogin();
        $response = $response->get('/admin/admin-user/list');
        $response->assertStatus(302);
    }

    public function testAdminPostAdminUser()
    {
        $response = $this->testAdminLogin();
        $response = $response->post('/admin/admin-user');
        $response->assertRedirect('/admin/admin-user');
    }

    public function testAdminGetAdminUserCreate()
    {
        $response = $this->testAdminLogin();
        $response = $response->get('/admin/admin-user/create');
        $response->assertStatus(200);
    }

    public function testAdminPostAdminUserCreateWithConfirm()
    {
        $response = $this->testAdminLogin();
        $param = [
            'name'=>'test',
            'login_id'=>'999999',
            'email'=>'test@test.com',
            'password'=>'00000000',
            'role'=>'manager',
            'action'=>'confirm',
        ];
        $response = $response->post('/admin/admin-user/create', $param);
        $response->assertStatus(200);
    }

    public function testAdminPostAdminUserCreateWithSave()
    {
        $response = $this->testAdminLogin();
        $param = [
            'name'=>'test',
            'login_id'=>'999999',
            'email'=>'test@test.com',
            'password'=>'00000000',
            'role'=>'manager',
            'action'=>'save',
        ];
        $response = $response->post('/admin/admin-user/create', $param);
        $response->assertRedirect('/admin/admin-user');
    }

    public function testAdminPostAdminUserCreateWithReturn()
    {
        $response = $this->testAdminLogin();
        $param = [
            'name'=>'test',
            'login_id'=>'999999',
            'email'=>'test@test.com',
            'password'=>'00000000',
            'role'=>'manager',
            'action'=>'return',
        ];
        $response = $response->post('/admin/admin-user/create', $param);
        $response->assertStatus(200);
    }

    public function testAdminGetAdminUserShow()
    {
        $id = 1;
        $user = AdminUser::find($id);
        $this->assertNotNull($user);

        $response = $this->testAdminLogin();
        $response = $response->get('/admin/admin-user/show/'.$id);
        $response->assertStatus(200);
    }

    public function testAdminGetAdminUserShowError()
    {
        $id = 9999;
        $user = AdminUser::find($id);
        $this->assertNull($user);

        $response = $this->testAdminLogin();
        $response = $response->get('/admin/admin-user/show/'.$id);
        $response->assertStatus(404);
    }

    public function testAdminGetAdminUserEdit()
    {
        $id = 1;
        $user = AdminUser::find($id);
        $this->assertNotNull($user);

        $response = $this->testAdminLogin();
        $response = $response->get('/admin/admin-user/edit/'.$id);
        $response->assertStatus(200);
    }

    public function testAdminGetAdminUserEditError()
    {
        $id = 9999;
        $user = AdminUser::find($id);
        $this->assertNull($user);

        $response = $this->testAdminLogin();
        $response = $response->get('/admin/admin-user/edit/'.$id);
        $response->assertStatus(404);
    }

    public function testAdminPutAdminUserEditWithConfirm()
    {
        $id = 4;
        $user = AdminUser::find($id);
        $this->assertNotNull($user);

        $response = $this->testAdminLogin();
        $param = [
            'name'=>'test',
            'login_id'=>'111111',
            'email'=>'test111@test.com',
            'password'=>'00000000',
            'role'=>'manager',
            'action'=>'confirm',
        ];
        $response = $response->put('/admin/admin-user/edit/'.$id, $param);
        $response->assertStatus(200);
    }

    public function testAdminPutAdminUserEditWithSave()
    {
        $id = 4;
        $user = AdminUser::find($id);
        $this->assertNotNull($user);

        $response = $this->testAdminLogin();
        $param = [
            'name'=>'test',
            'login_id'=>'111111',
            'email'=>'test111@test.com',
            'password'=>'00000000',
            'role'=>'manager',
            'action'=>'save',
        ];
        $response = $response->put('/admin/admin-user/edit/'.$id, $param);
        $response->assertRedirect('/admin/admin-user');
    }

    public function testAdminPutAdminUserEditWithReturn()
    {
        $id = 4;
        $user = AdminUser::find($id);
        $this->assertNotNull($user);

        $response = $this->testAdminLogin();
        $param = [
            'name'=>'test',
            'login_id'=>'111111',
            'email'=>'test111@test.com',
            'password'=>'00000000',
            'role'=>'manager',
            'action'=>'return',
        ];
        $response = $response->put('/admin/admin-user/edit/'.$id, $param);
        $response->assertStatus(200);
    }

    public function testAdminDeleteAdminUserDestroy()
    {
        $id = 5;
        $user = AdminUser::find($id);
        $this->assertNotNull($user);

        $response = $this->testAdminLogin();
        $response = $response->delete('/admin/admin-user/destroy/'.$id);
        $response->assertRedirect('/admin/admin-user');
    }

    public function testAdminDeleteAdminUserDestroyError()
    {
        $id = 9999;
        $user = AdminUser::find($id);
        $this->assertNull($user);

        $response = $this->testAdminLogin();
        $response = $response->delete('/admin/admin-user/destroy/'.$id);
        $response->assertStatus(404);
    }
}
