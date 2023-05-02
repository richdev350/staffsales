<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class WebGeneralHomeTest extends BaseTestCase
{
    protected $preserveGlobalState = FALSE;
    protected $runTestInSeparateProcess = TRUE;

    public function testIndex()
    {
        $response = $this->get('');
        $response->assertRedirect('/admin/login');
    }

}
