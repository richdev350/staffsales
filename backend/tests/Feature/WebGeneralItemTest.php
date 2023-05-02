<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Entities\Item;
use App\Models\Entities\ItemCategory;
use App\Models\Entities\Region;

class WebGeneralItemTest extends BaseTestCase
{
    protected $preserveGlobalState = FALSE;
    protected $runTestInSeparateProcess = TRUE;

    public function testSeeding(){
        \Artisan::call('migrate:fresh');
        \Artisan::call('db:seed');
        $this->assertTrue(true);
    }

    public function testGetItemList()
    {
        $item_category = ItemCategory::find(1);
        $this->assertNotNull($item_category);
        $response = $this->get('/item/list/1');
        $response->assertStatus(302);
    }

}
