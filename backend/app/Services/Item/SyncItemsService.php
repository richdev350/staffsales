<?php
declare(strict_types=1);

namespace App\Services\Item;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Models\Entities\Item;
use App\Models\Repositories\Contracts\ItemRepositoryInterface;
use App\Models\Repositories\Contracts\TagRepositoryInterface;
use App\Services\Traits\Conditionable;
use App\Services\Tag\CreateTagService;

class SyncItemsService
{
    private $repository;
    private $tagRepository;
    private $request;

    public function __construct(ItemRepositoryInterface $repository, TagRepositoryInterface $tagRepository, Request $request)
    {
        $this->repository = $repository;
        $this->request    = $request;
        $this->tagRepository = $tagRepository;
    }

    public function sync($jans=null)
    {
        try {
            if (!$jans) {
                $collection = $this->repository->list();
                $jans = $collection->pluck('jan')->all();
            }
            $jans = "'" . implode("','", $jans) ."'";

            $ecsite_database = DB::connection('mysql_ecsite');
            $query = "select * from items left join (select item_id, GROUP_CONCAT(tags.name) tags from tags left join items_tags on tags.id=items_tags.tag_id GROUP BY item_id) tags on items.id=tags.item_id where items.jan in ($jans)";

            $ecsite_items = collect($ecsite_database->select($query));
            foreach($ecsite_items as $data){
                $this->update($data);
            }

            return $ecsite_items;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    private function update($ecsite_item) {
        $item = $this->repository->findByJan($ecsite_item->jan);
        $item->name = $ecsite_item->name;
        $item->abridge = $ecsite_item->abridge;
        $item->summary = $ecsite_item->summary;
        $item->description_title = $ecsite_item->description_title;
        $item->description = $ecsite_item->description;
        $item->labels = $ecsite_item->labels?json_decode($ecsite_item->labels):null;
        $item->self_medication = $ecsite_item->self_medication;
        $item->spec = $ecsite_item->spec?json_decode($ecsite_item->spec):null;
        $item->is_stock = $ecsite_item->is_stock;
        $item->is_visible = $ecsite_item->is_visible;

        $item = $this->repository->persist($item);

        if($ecsite_item->tags){
            $tag_ids = [];

            $tag_names = explode(",", $ecsite_item->tags);
            foreach($tag_names as $tag_name){
                if(empty($tag_name)) 
                    continue;

                $tag = $this->tagRepository->findByName($tag_name);
                if(is_null($tag)){
                    $tag = $this->tagRepository->new(['name' => $tag_name]);
                    $this->tagRepository->persist($tag);
                }
                $tag_ids[] = $tag->id;
            }
        }
        if (isset($tag_ids) && is_array($tag_ids)) {
            $item->tags()->sync($tag_ids);
        }else{
            $item->tags()->sync([]);
        }

        
    }
}
