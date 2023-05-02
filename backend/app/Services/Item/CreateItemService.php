<?php
declare(strict_types=1);

namespace App\Services\Item;

use Throwable;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Application;
use App\Exceptions\ImageExifException;
use App\Models\Entities\Item;
use App\Models\Entities\File;
use App\Models\Repositories\Contracts\FileRepositoryInterface;
use App\Models\Repositories\Contracts\ItemRepositoryInterface;
use App\Http\Requests\Item\SaveItemRequestFilter;
use App\Http\Requests\Item\SaveItemRequest;
use App\Services\Traits\Filterable;
use App\Services\Traits\Validatable;
use App\Services\Tag\CreateTagService;
use App\Models\Entities\Tag;

class CreateItemService
{
    use Filterable,
        Validatable;

    private $repository;

    private $fileRepository;

    private $request;

    private $createTagService;

    public function __construct(
        ItemRepositoryInterface $repository,
        FileRepositoryInterface $fileRepository,
        CreateTagService $createTagService,
        Request $request
    ) {
        $this->repository        = $repository;
        $this->fileRepository    = $fileRepository;
        $this->request           = $request;
        $this->createTagService  = $createTagService;

        $this->setRequestFilter(new SaveItemRequestFilter());
        $this->setFormRequest(new SaveItemRequest());
        $this->init();
    }

    public function init()
    {
        if (! $this->request->isMethod('GET')) {
            $this->filterInputs();
            return;
        }

        $this->request->flush();

        $defaults = [
            'files'   => [],
            'old_file_ids'   => [],
        ];
        $this->request->merge($defaults);
    }

    public function create($inputs = null): Item
    {
        if (is_null($inputs)) {
            $inputs = $this->request->except('action');
        }
        try {
            return DB::transaction(function () use ($inputs) {
                if(!isset($inputs['spec'])){
                    $inputs['spec'] = [];
                }
                $item = $this->repository->new($inputs);

                $item = $this->repository->persist($item);

                $item->sort_no = $item->id;
                $item = $this->repository->persist($item);

                if (isset($inputs['item_category_ids']) && is_array($inputs['item_category_ids'])) {
                    $item->item_categories()->sync($inputs['item_category_ids']);
                }

                if(isset($inputs['tags'])){
                    $tag_ids = [];

                    $tag_names = explode(Tag::DELIMITER, $inputs['tags']);
                    foreach($tag_names as $tag_name){
                        if(empty($tag_name)) continue;
                        $tag = $this->createTagService->findByName($tag_name);
                        if(is_null($tag)){
                            $tag = $this->createTagService->create(['name' => $tag_name]);
                        }
                        $tag_ids[] = $tag->id;
                    }

                    if (isset($tag_ids) && is_array($tag_ids)) {
                        $item->tags()->sync($tag_ids);
                    }
                }

                if($this->request->has('files') && isset($inputs['files'])){
                    $file_ids = $this->saveFiles($inputs, $item);
                }

                if (isset($file_ids) && is_array($file_ids)) {
                    $item->files()->sync($file_ids);
                }

                return $item;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }

    private function saveFiles($inputs, $item): array
    {
        $count = 0;
        $file_ids = [];
        foreach ($inputs['files'] as $index => $item_file) {
            $image_info = getimagesize(public_path().$item_file);

            list($file_name, $file_extension) = explode('.', basename($item_file));
            $file_data = [
                'directory' => 'tmp',
                'name' => $file_name,
                'mime_type' => $image_info['mime'],
                'extension' => $file_extension,
                'size' => filesize(public_path().$item_file),
                'width' => $image_info[0],
                'height'  => $image_info[1],
            ];

            $file = $this->fileRepository->new($file_data);
            if(! \File::isDirectory(public_path().'/images/item/' . $item->id)){
                if(! \File::makeDirectory(public_path().'/images/item/' . $item->id)){
                    throw new Exception('mkdir error!');
                }
            }
            $file->directory = '/images/item/' . $item->id;

            copy(public_path().'/tmp/'.$file->basename, public_path().$file->directory.'/'.$file->basename);
            unlink(public_path().'/tmp/'.$file->basename);

            $file = $this->fileRepository->persist($file);

            $file_ids[$file->id]['sort_no'] = $count + 1;

            $count ++;
        }
        return $file_ids;
    }

}
