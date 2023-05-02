<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;

class WebApiImageTest extends BaseTestCase
{

    public function testPostImageUpload()
    {
        $uploadedFile = UploadedFile::fake()->image('dummy.jpg');
        $param = [
            'file' => $uploadedFile,
        ];
        $response = $this->post('/api/image/upload', $param, [
            'HTTP_REFERER' =>  url('/'),
        ]);
        $response->assertStatus(200);
    }

    public function testAdminPostImageUpload()
    {
        $response = $this->testAdminLogin();
        $uploadedFile = UploadedFile::fake()->image('dummy.jpg');
        $param = [
            'file' => $uploadedFile,
        ];
        $response = $this->post('/api/image/upload', $param, [
            'HTTP_REFERER' =>  url('/'),
        ]);
        $response->assertStatus(200);
    }
    public function testManagerPostImageUpload()
    {
        $response = $this->testManagerLogin();
        $uploadedFile = UploadedFile::fake()->image('dummy.jpg');
        $param = [
            'file' => $uploadedFile,
        ];
        $response = $this->post('/api/image/upload', $param, [
            'HTTP_REFERER' =>  url('/'),
        ]);
        $response->assertStatus(200);
    }
    public function testShopPostImageUpload()
    {
        $response = $this->testShopLogin();
        $uploadedFile = UploadedFile::fake()->image('dummy.jpg');
        $param = [
            'file' => $uploadedFile,
        ];
        $response = $this->post('/api/image/upload', $param, [
            'HTTP_REFERER' =>  url('/'),
        ]);
        $response->assertStatus(200);
    }

}
