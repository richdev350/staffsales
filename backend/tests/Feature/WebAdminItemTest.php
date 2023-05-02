<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Entities\Item;

class WebAdminItemTest extends BaseTestCase
{

    public function testGetItemList()
    {
        $response = $this->get('/admin/item/list');
        $response->assertStatus(302);
    }

    public function testAdminGetItemList()
    {
        $response = $this->testAdminLogin();
        $response = $response->get('/admin/item/list');
        $response->assertStatus(200);
    }

    public function testManagerGetItemList()
    {
        $response = $this->testManagerLogin();
        $response = $response->get('/admin/item/list');
        $response->assertStatus(302);
    }

    public function testShopGetItemList()
    {
        $response = $this->testShopLogin();
        $response = $response->get('/admin/item/list');
        $response->assertStatus(302);
    }

    public function testAdminPostItem()
    {
        $response = $this->testAdminLogin();
        $response = $response->post('/admin/item');
        $response->assertRedirect('/admin/item');
    }

    public function testAdminPostItemSort()
    {
        $response = $this->testAdminLogin();
        $param = [
            'ids' => [1,2,3,4,5,6,7,8,9,10],
            'page' => 1,
        ];
        $response = $response->post('/admin/item/sort', $param);
        $response->assertRedirect('/admin/item?page=1');
    }

    public function testAdminPostItemSortExchangeWithUp()
    {
        $response = $this->testAdminLogin();
        $param = [
            'id' => 5,
            'page' => 1,
            'type' => 'up',
        ];
        $response = $response->post('/admin/item/sort_exchange', $param);
        $response->assertRedirect('/admin/item?page=1');
    }

    public function testAdminPostItemSortExchangeWithDown()
    {
        $response = $this->testAdminLogin();
        $param = [
            'id' => 1,
            'page' => 1,
            'type' => 'down',
        ];
        $response = $response->post('/admin/item/sort_exchange', $param);
        $response->assertRedirect('/admin/item?page=1');
    }

    public function testAdminPostItemBatchWithHide()
    {
        $response = $this->testAdminLogin();
        $param = [
            'targets' => '1,2,3',
            'action' => 'hide',
        ];
        $response = $response->post('/admin/item/batch', $param);
        $response->assertRedirect('/admin/item');
    }

    public function testAdminPostItemBatchWithShow()
    {
        $response = $this->testAdminLogin();
        $param = [
            'targets' => '1,2,3',
            'action' => 'show',
        ];
        $response = $response->post('/admin/item/batch', $param);
        $response->assertRedirect('/admin/item');
    }

    public function testAdminGetItemCreate()
    {
        $response = $this->testAdminLogin();
        $response = $response->get('/admin/item/create');
        $response->assertStatus(200);
    }

    public function testAdminPostItemCreateWithConfirm()
    {
        $response = $this->testAdminLogin();
        $param = [
            'name'=>'5種のアソートケーキ+',
            'maker_id'=>'1',
            'jan'=>'4990000000001',
            'price'=>'2680',
            'max_amount'=>'9',
            'is_stock'=>'0',
            'item_category_ids'=>[1,2],
            'region_ids'=>[1,2],
            'comment'=>'カットがいらないショートケーキタイプ（苺別添2個）（直径18cm）（4~8名様向け）',
            'note'=>'選んで楽しい、みんなHAPPY5種の味わい。5種の美味しさが楽しめるケーキセット。（苺、ガトーショコラ、渋皮マロン、チョコレート、チーズ）',
            'spec'=>[['title'=>'賞味期限', 'body'=>'製造日より2日']],
            'action'=>'confirm',
        ];
        $response = $response->post('/admin/item/create', $param);
        $response->assertStatus(200);
    }

    public function testAdminPostItemCreateWithSave()
    {
        $response = $this->testAdminLogin();
        $param = [
            'name'=>'5種のアソートケーキ+',
            'maker_id'=>'1',
            'jan'=>'4990000000001',
            'price'=>'2680',
            'max_amount'=>'9',
            'is_stock'=>'0',
            'item_category_ids'=>[1,2],
            'region_ids'=>[1,2],
            'comment'=>'カットがいらないショートケーキタイプ（苺別添2個）（直径18cm）（4~8名様向け）',
            'note'=>'選んで楽しい、みんなHAPPY5種の味わい。5種の美味しさが楽しめるケーキセット。（苺、ガトーショコラ、渋皮マロン、チョコレート、チーズ）',
            'spec'=>[['title'=>'賞味期限', 'body'=>'製造日より2日']],
            'action'=>'save',
        ];
        $response = $response->post('/admin/item/create', $param);
        $response->assertRedirect('/admin/item');
    }

    public function testAdminPostItemCreateWithReturn()
    {
        $response = $this->testAdminLogin();
        $param = [
            'name'=>'5種のアソートケーキ+',
            'maker_id'=>'1',
            'jan'=>'4990000000001',
            'price'=>'2680',
            'max_amount'=>'9',
            'is_stock'=>'0',
            'item_category_ids'=>[1,2],
            'region_ids'=>[1,2],
            'comment'=>'カットがいらないショートケーキタイプ（苺別添2個）（直径18cm）（4~8名様向け）',
            'note'=>'選んで楽しい、みんなHAPPY5種の味わい。5種の美味しさが楽しめるケーキセット。（苺、ガトーショコラ、渋皮マロン、チョコレート、チーズ）',
            'spec'=>[['title'=>'賞味期限', 'body'=>'製造日より2日']],
            'action'=>'return',
        ];
        $response = $response->post('/admin/item/create', $param);
        $response->assertStatus(200);
    }

    public function testAdminGetItemShow()
    {
        $id = 1;
        $item = Item::find($id);
        $this->assertNotNull($item);

        $response = $this->testAdminLogin();
        $response = $response->get('/admin/item/show/'.$id);
        $response->assertStatus(200);
    }

    public function testAdminGetItemShowError()
    {
        $id = 9999;
        $item = Item::find($id);
        $this->assertNull($item);

        $response = $this->testAdminLogin();
        $response = $response->get('/admin/item/show/'.$id);
        $response->assertStatus(404);
    }

    public function testAdminGetItemEdit()
    {
        $id = 1;
        $item = Item::find($id);
        $this->assertNotNull($item);

        $response = $this->testAdminLogin();
        $response = $response->get('/admin/item/edit/'.$id);
        $response->assertStatus(200);
    }

    public function testAdminGetItemEditError()
    {
        $id = 9999;
        $item = Item::find($id);
        $this->assertNull($item);

        $response = $this->testAdminLogin();
        $response = $response->get('/admin/item/edit/'.$id);
        $response->assertStatus(404);
    }

    public function testAdminPutItemEditWithConfirm()
    {
        $id = 4;
        $item = Item::find($id);
        $this->assertNotNull($item);

        $response = $this->testAdminLogin();
        $param = [
            'name'=>'5種のアソートケーキ+',
            'maker_id'=>'1',
            'jan'=>'4999000000001',
            'price'=>'2680',
            'max_amount'=>'9',
            'is_stock'=>'0',
            'item_category_ids'=>[1,2],
            'region_ids'=>[1,2],
            'comment'=>'カットがいらないショートケーキタイプ（苺別添2個）（直径18cm）（4~8名様向け）',
            'note'=>'選んで楽しい、みんなHAPPY5種の味わい。5種の美味しさが楽しめるケーキセット。（苺、ガトーショコラ、渋皮マロン、チョコレート、チーズ）',
            'spec'=>[['title'=>'賞味期限', 'body'=>'製造日より2日']],
            'action'=>'confirm',
        ];
        $response = $response->put('/admin/item/edit/'.$id, $param);
        $response->assertStatus(200);
    }

    public function testAdminPutItemEditWithSave()
    {
        $id = 4;
        $item = Item::find($id);
        $this->assertNotNull($item);

        $response = $this->testAdminLogin();
        $param = [
            'name'=>'5種のアソートケーキ+',
            'maker_id'=>'1',
            'jan'=>'4999000000001',
            'price'=>'2680',
            'max_amount'=>'9',
            'is_stock'=>'0',
            'item_category_ids'=>[1,2],
            'region_ids'=>[1,2],
            'comment'=>'カットがいらないショートケーキタイプ（苺別添2個）（直径18cm）（4~8名様向け）',
            'note'=>'選んで楽しい、みんなHAPPY5種の味わい。5種の美味しさが楽しめるケーキセット。（苺、ガトーショコラ、渋皮マロン、チョコレート、チーズ）',
            'spec'=>[['title'=>'賞味期限', 'body'=>'製造日より2日']],
            'action'=>'save',
        ];
        $response = $response->put('/admin/item/edit/'.$id, $param);
        $response->assertRedirect('/admin/item');
    }

    public function testAdminPutItemEditWithReturn()
    {
        $id = 4;
        $item = Item::find($id);
        $this->assertNotNull($item);

        $response = $this->testAdminLogin();
        $param = [
            'name'=>'5種のアソートケーキ+',
            'maker_id'=>'1',
            'jan'=>'4999000000001',
            'price'=>'2680',
            'max_amount'=>'9',
            'is_stock'=>'0',
            'item_category_ids'=>[1,2],
            'region_ids'=>[1,2],
            'comment'=>'カットがいらないショートケーキタイプ（苺別添2個）（直径18cm）（4~8名様向け）',
            'note'=>'選んで楽しい、みんなHAPPY5種の味わい。5種の美味しさが楽しめるケーキセット。（苺、ガトーショコラ、渋皮マロン、チョコレート、チーズ）',
            'spec'=>[['title'=>'賞味期限', 'body'=>'製造日より2日']],
            'action'=>'return',
        ];
        $response = $response->put('/admin/item/edit/'.$id, $param);
        $response->assertStatus(200);
    }

    public function testAdminDeleteItemDestroy()
    {
        $id = 5;
        $item = Item::find($id);
        $this->assertNotNull($item);

        $response = $this->testAdminLogin();
        $response = $response->delete('/admin/item/destroy/'.$id);
        $response->assertRedirect('/admin/item');
    }

    public function testAdminDeleteItemDestroyError()
    {
        $id = 9999;
        $item = Item::find($id);
        $this->assertNull($item);

        $response = $this->testAdminLogin();
        $response = $response->delete('/admin/item/destroy/'.$id);
        $response->assertStatus(404);
    }

}
