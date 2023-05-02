<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Entities\DesiredTime;

class WebAdminDesiredTimeTest extends BaseTestCase
{

    public function testGetDesiredTimeList()
    {
        $response = $this->get('/admin/desired-time/list');
        $response->assertStatus(302);
    }

    public function testAdminGetDesiredTimeList()
    {
        $response = $this->testAdminLogin();
        $response = $response->get('/admin/desired-time/list');
        $response->assertStatus(200);
    }

    public function testManagerGetDesiredTimeList()
    {
        $response = $this->testManagerLogin();
        $response = $response->get('/admin/desired-time/list');
        $response->assertStatus(302);
    }

    public function testShopGetDesiredTimeList()
    {
        $response = $this->testShopLogin();
        $response = $response->get('/admin/desired-time/list');
        $response->assertStatus(302);
    }

    public function testAdminPostDesiredTime()
    {
        $response = $this->testAdminLogin();
        $response = $response->post('/admin/desired-time');
        $response->assertRedirect('/admin/desired-time');
    }

    public function testAdminGetDesiredTimeCreate()
    {
        $response = $this->testAdminLogin();
        $response = $response->get('/admin/desired-time/create');
        $response->assertStatus(200);
    }

    public function testAdminPostDesiredTimeCreateWithConfirm()
    {
        $response = $this->testAdminLogin();
        $param = [
            'from'=>'11',
            'to'=>'12',
            'action'=>'confirm',
        ];
        $response = $response->post('/admin/desired-time/create', $param);
        $response->assertStatus(200);
    }

    public function testAdminPostDesiredTimeCreateWithSave()
    {
        $response = $this->testAdminLogin();
        $param = [
            'from'=>'11',
            'to'=>'12',
            'action'=>'save',
        ];
        $response = $response->post('/admin/desired-time/create', $param);
        $response->assertRedirect('/admin/desired-time');
    }

    public function testAdminPostDesiredTimeCreateWithReturn()
    {
        $response = $this->testAdminLogin();
        $param = [
            'from'=>'11',
            'to'=>'12',
            'action'=>'return',
        ];
        $response = $response->post('/admin/desired-time/create', $param);
        $response->assertStatus(200);
    }

    public function testAdminGetDesiredTimeShow()
    {
        $id = 1;
        $desired_time = DesiredTime::find($id);
        $this->assertNotNull($desired_time);

        $response = $this->testAdminLogin();
        $response = $response->get('/admin/desired-time/show/'.$id);
        $response->assertStatus(200);
    }

    public function testAdminGetDesiredTimeShowError()
    {
        $id = 9999;
        $desired_time = DesiredTime::find($id);
        $this->assertNull($desired_time);

        $response = $this->testAdminLogin();
        $response = $response->get('/admin/desired-time/show/'.$id);
        $response->assertStatus(404);
    }

    public function testAdminGetDesiredTimeEdit()
    {
        $id = 1;
        $desired_time = DesiredTime::find($id);
        $this->assertNotNull($desired_time);

        $response = $this->testAdminLogin();
        $response = $response->get('/admin/desired-time/edit/'.$id);
        $response->assertStatus(200);
    }

    public function testAdminGetDesiredTimeEditError()
    {
        $id = 9999;
        $desired_time = DesiredTime::find($id);
        $this->assertNull($desired_time);

        $response = $this->testAdminLogin();
        $response = $response->get('/admin/desired-time/edit/'.$id);
        $response->assertStatus(404);
    }

    public function testAdminPutDesiredTimeEditWithConfirm()
    {
        $id = 4;
        $desired_time = DesiredTime::find($id);
        $this->assertNotNull($desired_time);

        $response = $this->testAdminLogin();
        $param = [
            'from'=>'11',
            'to'=>'12',
            'action'=>'confirm',
        ];
        $response = $response->put('/admin/desired-time/edit/'.$id, $param);
        $response->assertStatus(200);
    }

    public function testAdminPutDesiredTimeEditWithSave()
    {
        $id = 4;
        $desired_time = DesiredTime::find($id);
        $this->assertNotNull($desired_time);

        $response = $this->testAdminLogin();
        $param = [
            'from'=>'11',
            'to'=>'12',
            'action'=>'save',
        ];
        $response = $response->put('/admin/desired-time/edit/'.$id, $param);
        $response->assertRedirect('/admin/desired-time');
    }

    public function testAdminPutDesiredTimeEditWithReturn()
    {
        $id = 4;
        $desired_time = DesiredTime::find($id);
        $this->assertNotNull($desired_time);

        $response = $this->testAdminLogin();
        $param = [
            'from'=>'11',
            'to'=>'12',
            'action'=>'return',
        ];
        $response = $response->put('/admin/desired-time/edit/'.$id, $param);
        $response->assertStatus(200);
    }

    public function testAdminDeleteDesiredTimeDestroy()
    {
        $desired_time = DesiredTime::all()->first();
        $this->assertNotNull($desired_time);

        $response = $this->testAdminLogin();
        $response = $response->delete('/admin/desired-time/destroy/'.$desired_time->id);
        $response->assertRedirect('/admin/desired-time');
    }

    public function testAdminDeleteDesiredTimeDestroyError()
    {
        $id = 9999;
        $desired_time = DesiredTime::find($id);
        $this->assertNull($desired_time);

        $response = $this->testAdminLogin();
        $response = $response->delete('/admin/desired-time/destroy/'.$id);
        $response->assertStatus(404);
    }

}
