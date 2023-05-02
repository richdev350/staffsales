<?php
declare(strict_types=1);

namespace App\Services\Traits;

use InvalidArgumentException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File as FileSystemFile;
use Illuminate\Http\Request;
use App\Models\Entities\File;

/**
 * ファイルアップロード用トレイト
 */
trait FileUploadable
{
    /**
     * @var array
     */
    protected $fileAttributes;

    /**
     *
     */
    public function uploadFiles()
    {
        if (! $this->request instanceof Request) {
            throw new InvalidArgumentException('This service must have $request parameter of the \Illuminate\Http\Request instance.');
        }

        foreach ($this->fileAttributes as $fileAttribute) {
            if (! $this->request->hasFile($fileAttribute)) {
                continue;
            }

            $uploadedFile = $this->request->file($fileAttribute);

            $extension = strtolower($uploadedFile->getClientOriginalExtension());
            $extension = 'jpeg' != $extension ? $extension : 'jpg';
            $source    = $uploadedFile->getRealPath();
            $imageInfo = getimagesize($source);

            $file = new File([
                'directory'            => 'tmp',
                'name'                 => sha1(uniqid((string) mt_rand(), true)),
                'client_original_name' => $uploadedFile->getClientOriginalName(),
                'mime_type'            => $uploadedFile->getMimeType(),
                'extension'            => $extension,
                'size'                 => $uploadedFile->getSize(),
                'hash'                 => sha1_file($source),
                'width'                => $imageInfo[0] ?? null,
                'height'               => $imageInfo[1] ?? null,
            ]);

            Storage::disk('public')->putFileAs(
                $file->directory,
                new FileSystemFile($source),
                $file->basename
            );

            $fileDataAttribute = "{$fileAttribute}_data";

            $fileData = $this->request->input($fileDataAttribute);
            if (! empty($fileData['id'])) {
                $file->id = $fileData['id'];
            }

            $mergeData = [
                $fileDataAttribute => array_merge($file->toArray(), [
                    'url' => $file->url,
                ]),
            ];

            $this->request->merge($mergeData);
        }
    }
}
