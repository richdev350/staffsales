<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Entities\Maker;

class WebAdminMakerTest extends BaseTestCase
{

    public function testGetMakerList()
    {
        $response = $this->get('/admin/maker/list');
        $response->assertStatus(302);
    }

    public function testAdminGetMakerList()
    {
        $response = $this->testAdminLogin();
        $response = $response->get('/admin/maker/list');
        $response->assertStatus(200);
    }

    public function testManagerGetMakerList()
    {
        $response = $this->testManagerLogin();
        $response = $response->get('/admin/maker/list');
        $response->assertStatus(302);
    }

    public function testShopGetMakerList()
    {
        $response = $this->testShopLogin();
        $response = $response->get('/admin/maker/list');
        $response->assertStatus(302);
    }

    public function testAdminPostMaker()
    {
        $response = $this->testAdminLogin();
        $response = $response->post('/admin/maker');
        $response->assertRedirect('/admin/maker');
    }

    public function testAdminGetMakerCreate()
    {
        $response = $this->testAdminLogin();
        $response = $response->get('/admin/maker/create');
        $response->assertStatus(200);
    }

    public function testAdminPostMakerCreateWithConfirm()
    {
        $response = $this->testAdminLogin();
        $param = [
            'name'=>'テストメーカー1',
            'action'=>'confirm',
        ];
        $response = $response->post('/admin/maker/create', $param);
        $response->assertStatus(200);
    }

    public function testAdminPostMakerCreateWithSave()
    {
        $response = $this->testAdminLogin();
        $param = [
            'name'=>'テストメーカー1',
            'action'=>'save',
        ];
        $response = $response->post('/admin/maker/create', $param);
        $response->assertRedirect('/admin/maker');
    }

    public function testAdminPostMakerCreateWithReturn()
    {
        $response = $this->testAdminLogin();
        $param = [
            'name'=>'テストメーカー1',
            'action'=>'return',
        ];
        $response = $response->post('/admin/maker/create', $param);
        $response->assertStatus(200);
    }

    public function testAdminGetMakerShow()
    {
        $id = 1;
        $maker = Maker::find($id);
        $this->assertNotNull($maker);

        $response = $this->testAdminLogin();
        $response = $response->get('/admin/maker/show/'.$id);
        $response->assertStatus(200);
    }

    public function testAdminGetMakerShowError()
    {
        $id = 9999;
        $maker = Maker::find($id);
        $this->assertNull($maker);

        $response = $this->testAdminLogin();
        $response = $response->get('/admin/maker/show/'.$id);
        $response->assertStatus(404);
    }

    public function testAdminGetMakerEdit()
    {
        $id = 1;
        $maker = Maker::find($id);
        $this->assertNotNull($maker);

        $response = $this->testAdminLogin();
        $response = $response->get('/admin/maker/edit/'.$id);
        $response->assertStatus(200);
    }

    public function testAdminGetMakerEditError()
    {
        $id = 9999;
        $maker = Maker::find($id);
        $this->assertNull($maker);

        $response = $this->testAdminLogin();
        $response = $response->get('/admin/maker/edit/'.$id);
        $response->assertStatus(404);
    }

    public function testAdminPutMakerEditWithConfirm()
    {
        $id = 4;
        $maker = Maker::find($id);
        $this->assertNotNull($maker);

        $response = $this->testAdminLogin();
        $param = [
            'name'=>'テストメーカー2',
            'action'=>'confirm',
        ];
        $response = $response->put('/admin/maker/edit/'.$id, $param);
        $response->assertStatus(200);
    }

    public function testAdminPutMakerEditWithSave()
    {
        $id = 4;
        $maker = Maker::find($id);
        $this->assertNotNull($maker);

        $response = $this->testAdminLogin();
        $param = [
            'name'=>'テストメーカー2',
            'action'=>'save',
        ];
        $response = $response->put('/admin/maker/edit/'.$id, $param);
        $response->assertRedirect('/admin/maker');
    }

    public function testAdminPutMakerEditWithReturn()
    {
        $id = 4;
        $maker = Maker::find($id);
        $this->assertNotNull($maker);

        $response = $this->testAdminLogin();
        $param = [
            'name'=>'テストメーカー2',
            'action'=>'return',
        ];
        $response = $response->put('/admin/maker/edit/'.$id, $param);
        $response->assertStatus(200);
    }

    public function testAdminDeleteMakerDestroy()
    {
        $id = 5;
        $maker = Maker::find($id);
        $this->assertNotNull($maker);

        $response = $this->testAdminLogin();
        $response = $response->delete('/admin/maker/destroy/'.$id);
        $response->assertRedirect('/admin/maker');
    }

    public function testAdminDeleteMakerDestroyError()
    {
        $id = 9999;
        $maker = Maker::find($id);
        $this->assertNull($maker);

        $response = $this->testAdminLogin();
        $response = $response->delete('/admin/maker/destroy/'.$id);
        $response->assertStatus(404);
    }

}
