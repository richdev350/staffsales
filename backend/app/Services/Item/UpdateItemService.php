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
use App\Models\Entities\Tag;
use App\Services\Tag\CreateTagService;

class UpdateItemService
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

        $item = $this->repository->find((int) $this->request->offsetGet('id'));

        $defaults = [
            'files'             => $item->files,
            'item_category_ids' => $item->item_categories->pluck('id')->all(),
            'maker_id'          => (integer) $item->maker_id,
            'jan'               => (string) $item->jan,
            'name'              => (string) $item->name,
            'abridge'           => (string) $item->abridge,
            'summary'           => (string) $item->summary,
            'description_title' => (string) $item->description_title,
            'description'       => (string) $item->description,
            'notes'             => (string) $item->notes,
            'self_medication'   => (integer) $item->self_medication,
            'labels'            => $item->labels,
            'tags'              => $item->tags_text,
            'price'             => (integer) $item->price,
            'is_stock'          => (integer) $item->is_stock,
            'is_visible'        => (integer) $item->is_visible,
            'max_amount'        => (integer) $item->max_amount,
            'spec'              => $item->spec,
        ];
        $this->request->merge($defaults);
    }

    public function update($inputs = null): Item
    {
        if (is_null($inputs)) {
            $inputs = $this->request->except('action');
        }
        try {
            return DB::transaction(function () use ($inputs) {
                if ($this->request->offsetExists('id')) {
                    $item = $this->repository->find((int) $this->request->offsetGet('id'));
                } else if (isset($inputs['id'])) {
                    $item = $this->repository->find((int) $inputs['id']);
                }
                if(!isset($inputs['spec'])){
                    $inputs['spec'] = [];
                }
                $item = $this->repository->edit($item, $inputs);

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
                }
                if (isset($tag_ids) && is_array($tag_ids)) {
                    $item->tags()->sync($tag_ids);
                }else{
                    $item->tags()->sync([]);
                }

                if($this->request->has('files') && isset($inputs['files'])){
                    $file_ids = $this->saveFiles($inputs, $item);
                }

                if (isset($file_ids) && is_array($file_ids)) {
                    $item->files()->sync($file_ids);
                }else{
                    $item->files()->sync([]);
                }

                clearCategoryCache();

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
            if (preg_match('/\/tmp\//', $item_file)) {
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
                if (! empty($inputs['old_file_ids'][$count])) {
                    $file = $this->fileRepository->find((int) $inputs['old_file_ids'][$count]);
                    $old_file = $file->directory . '/' . $file->basename;
                    $file_data['directory'] = $file->directory;
                    $file = $this->fileRepository->edit($file, $file_data);
                } else {
                    $file = $this->fileRepository->new($file_data);
                    if(! \File::isDirectory(public_path().'/images/item/' . $item->id)){
                        if(! \File::makeDirectory(public_path().'/images/item/' . $item->id)){
                            throw new Exception('mkdir error!');
                        }
                    }
                    $file->directory = '/images/item/' . $item->id;

                }
                copy(public_path().'/tmp/'.$file->basename, public_path().'/'.$file->directory.'/'.$file->basename);
                unlink(public_path().'/tmp/'.$file->basename);

                $file = $this->fileRepository->persist($file);

                $file_ids[$file->id]['sort_no'] = $count + 1;
            }else{
                if (! empty($inputs['old_file_ids'][$count]) && empty($item_file)) {
                    $file = $this->fileRepository->find((int) $inputs['old_file_ids'][$count]);
                    $file->delete();
                    $old_file = $file->directory . '/' . $file->basename;
                }else{
                    $file_ids[$inputs['old_file_ids'][$count]]['sort_no'] = $count + 1;
                }
            }
            if (isset($old_file)) {
                if(\File::exists(public_path().'/'.$old_file)){
                    unlink(public_path().'/'.$old_file);
                }
                unset($old_file);
            }

            $count ++;
        }
        return $file_ids;
    }

    public function setSortNum($ids): Bool
    {
        try {
            if(!is_array($ids)){
                return false;
            }
            return DB::transaction(function () use ($ids) {
                $sort_no_list = [];
                $conditions = [
                    'ids' => $ids,
                    'orders' => ['sort_no' => 'ASC'],
                ];
                $items = $this->repository->list($conditions);
                if($items){
                    foreach($items as $item){
                        $sort_no_list[] = $item->sort_no;
                    }
                }
                foreach($ids as $index => $id){
                    $item = $this->repository->find((int) $id);
                    $item->sort_no = $sort_no_list[$index];
                    $item = $this->repository->persist($item);
                }
                return true;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }

    public function exchangeSortNum($id, $type, $conditions): Bool
    {
        try {
            if(!$id || !$type){
                return false;
            }
            return DB::transaction(function () use ($id, $type, $conditions) {
                $item = $this->repository->find((int) $id);
                if(!$item){
                    return false;
                }
                $sort_no = $item->sort_no;
                if($type == 'up'){
                    $order = 'DESC';
                }else{
                    $order = 'ASC';
                }
                $conditions['sort_no_'.$type] = $sort_no;
                $conditions['orders'] = ['sort_no' => $order];
                $neighbor_item = $this->repository->list($conditions)->first();
                if(!$neighbor_item){
                    return false;
                }
                $item->sort_no = $neighbor_item->sort_no;
                $neighbor_item->sort_no = $sort_no;

                $item = $this->repository->persist($item);
                $neighbor_item = $this->repository->persist($neighbor_item);

                return true;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }

}
