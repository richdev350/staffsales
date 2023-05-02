<?php
declare(strict_types=1);

namespace App\Services\ItemCategory;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Models\Entities\ItemCategory;
use App\Models\Repositories\Contracts\ItemCategoryRepositoryInterface;
use App\Models\Repositories\Eloquent\ItemRepository;
use App\Services\Traits\Conditionable;

class ListItemCategoriesService
{
    use Conditionable;

    private $repository;
    private $request;

    public function __construct(ItemCategoryRepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request    = $request;
    }

    public function list($conditions = null, $limit = null, $offset = null): Collection
    {
        if (! is_array($conditions)) {
            $conditions = $this->conditionQueryToArray($conditions);
        }
        if(! isset($conditions['orders']) && ! isset($conditions['orderby']) && ! isset($conditions['orderByRaws'])){
            $conditions['orders'] = [
                'id' => 'ASC',
            ];
        }

        return $this->repository->list($conditions, $limit, $offset);
    }

    public function paginate($conditions = null, int $perPage = 10): LengthAwarePaginator
    {
        if (! is_array($conditions)) {
            $conditions = $this->conditionQueryToArray($conditions);
        }
        if(! isset($conditions['orders']) && ! isset($conditions['orderby']) && ! isset($conditions['orderByRaws'])){
            $conditions['orders'] = [
                'id' => 'ASC',
            ];
        }

        return $this->repository->paginate($conditions, $perPage);
    }

    public function rootList(): Collection
    {
        return $this->repository->findAllByParentId(null);
    }

    public function getOrCreateRootList(): Collection
    {
        $itemCategories = $this->rootList();
        if(0 !== count($itemCategories)){
            return $itemCategories;
        }

        try {
            return DB::transaction(function () use($itemCategories){
                $inputs = [
                    'name' => '新しいカテゴリ',
                ];
                $item_category = $this->repository->new($inputs);
                $item_category = $this->repository->persist($item_category);

                return $this->repository->findAllByParentId(null);
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }

    /**
     * CloosureTableエンティティリストからJsTree用の主要Jsonを作成する
     */
    public function getJsTreeJson($closure_entity_list, array $checked_ids = [], bool $is_confirm = false){
        $json = "  {\"data\":[";
        $json .=       $this->getRecursionSubJsTreeJson($closure_entity_list, $checked_ids, $is_confirm);
        $json .= "     ]";
        $json .= "  }";

        return $json ;
    }

    /**
     * CloosureTableエンティティリストからJsTree用のサブJsonを再帰的に作成する
     */
    private function getRecursionSubJsTreeJson($closure_entities, array $checked_ids = [], bool $is_confirm = false){
        $json = "";
        foreach($closure_entities as $closure_entity){
            $json .= "{";
            $json .= "\"id\":\"$closure_entity->id\",";

            $class_one = $closure_entity->class_one ? 1:0;
            $json .= "\"li_attr\":{\"class_one\" : \"$class_one\"},";

            if($closure_entity->class_one){
                $json .= "\"icon\":\"fas fa-user-md\",";
            }else{
                $json .= "\"icon\":\"none\",";
            }

            $json .= "\"state\": {";
            if(in_array($closure_entity->id, $checked_ids)){
                $json .= "\"selected\" : true,";
            }
            if($is_confirm){
                $json .= "\"disabled\" : true,";
            }
            if(',' === substr($json, -1)){
                $json = substr($json, 0, -1);
            }
            $json .= "},";

            $json .= "\"text\":\"$closure_entity->name\"";
            if($closure_entity->hasChildren()){
                $json .= ",\"children\":[";
                $json .= $this->getRecursionSubJsTreeJson($closure_entity->getChildren()->sortBy('position'), $checked_ids, $is_confirm);
                $json .= "]";
            }
            if($closure_entity->hasNextSiblings()){
                $json .= "},";
            }else{
                $json .= "}";
            }
        }
        return $json;
    }

    private function getRecursionArray($closure_entities, $disable_category_class_one, $disable_category_special, $category_coumt_map){
        $categories = [];
        foreach($closure_entities as $closure_entity){

            if ($disable_category_class_one && $closure_entity->class_one) {
                continue;
            }

            if ($disable_category_special && $closure_entity->id == ItemRepository::ITEM_CATEGORY_ID_SPECIAL) {
                continue;
            }

            $children = [];
            if($closure_entity->hasChildren()){
                $children = $this->getRecursionArray($closure_entity->getChildren()->sortBy('position'), $disable_category_class_one, $disable_category_special, $category_coumt_map);
            }

            $count = 0;
            if (isset($category_coumt_map[$closure_entity->id])) {
                $count = $category_coumt_map[$closure_entity->id];
            }

            $category = [
                "id" => $closure_entity->id,
                "name" => $closure_entity->name,
                "items_count" => $count,
                "children" => $children,
            ];

            array_push($categories, $category);
        }
        return $categories;
    }

    public function getAllArray($closure_entities, $disable_category_class_one, $disable_category_special){
        $category_count_list = $this->repository->findItemsCountPerCategory($disable_category_class_one, $disable_category_special);
        $category_coumt_map = [];
        foreach($category_count_list as $category_count) {
            $category_coumt_map[$category_count->item_category_id] = $category_count->total_count;
        }

        return $this->getRecursionArray($closure_entities, $disable_category_class_one, $disable_category_special, $category_coumt_map);
    }

    public function find($id): ItemCategory
    {
        return $this->repository->findById($id);
    }
}
