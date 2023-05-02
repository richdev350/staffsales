<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Throwable;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;

class UploadController extends Controller
{
    /**
     * Dropzoneからのファイルアップロード
     */
    public function upload(Request $request)
    {
        try {
            $file = $request->file('file');
            if (empty($file) ) {
                throw new Exception('No file!');
			}
            $image_info = getimagesize($file->getRealPath());
            $alow_mime_type = ['image/gif', 'image/png', 'image/jpeg'];
            if(!in_array($image_info['mime'], $alow_mime_type)){
                throw new Exception('アップロード出来る画像はjpg、png、gifのみです。');
            }
            if(!copy($file->getRealPath(), public_path().'/tmp/'.$file->hashName())){
                throw new Exception('ファイルの保存に失敗しました。');
            }
            $image = \Image::make(public_path().'/tmp/'.$file->hashName());
            $exif = $image->exif();
            if($exif){
                // Exifが取得できる場合のみ画像ローテーションの修正
                if(isset($exif['Orientation'])){
                    $rotate_image = \Image::make(public_path().'/tmp/'.$file->hashName())->orientate();
                    $rotate_image->save(public_path().'/tmp/'.$file->hashName());
                }
            }
            return response()->json(['status' => 200, 'result' => "OK", 'data' => '/tmp/'.$file->hashName()]);
        } catch(Exception $e) {
            return response()->json(['status' => 400, 'result' => "NG", 'data' => $e->getMessage()]);
        }
    }
}
