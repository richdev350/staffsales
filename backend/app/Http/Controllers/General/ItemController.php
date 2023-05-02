<?php
declare(strict_types=1);

namespace App\Http\Controllers\General;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\CartController as ApiCartController;
use App\Models\Entities\Item;
use App\Services\Item\ListItemsService;
use App\Services\ItemCategory\ListItemCategoriesService;
use App\Services\Publish\ListPublishService;
use DateTime;
use App\Enums\Mode\Modes;

class ItemController extends BaseGeneralController
{
    public const DEFAULT_COUNT  = 15;
    public const DEFAULT_SORT   = "sort_no";

    public function list(
        ListItemsService $listItemsService,
        ListItemCategoriesService $listItemCategoriesService,
        ListPublishService $listPublishService,
        $item_category_id = null,
        $condition = null
    ) {
        $conditions = [];

        $item_category = null;
        if ($item_category_id != null) {
            $conditions['item_category_id'] = $item_category_id;
            $item_category = $listItemCategoriesService->find((int)$item_category_id);
        }
        $conditions['is_visible'] = 1;
        if (!isset($conditions['count'])) {
            $conditions['count'] = ItemController::DEFAULT_COUNT;
        }
        if (!isset($conditions['sort'])) {
            $conditions['sort'] = ItemController::DEFAULT_SORT;
        }

        $conditions['orders'] = ['sort_no' => 'ASC'];

        $paginator = $listItemsService->paginate($conditions, (int)$conditions['count']);
        $root_item_categories = $listItemCategoriesService->rootList();

        $current_mode = $this->mode($listPublishService);
        $updated_at = 0;
        $publish_condition = $listPublishService->conditionQueryToArray($condition);
        $publish = $listPublishService->list($publish_condition);
        if (count($publish) > 0) {
            $updated_at = strtotime($publish->first()->updated_at->format('Y-m-d H:i:s'));
        }

        if ($current_mode == Modes::MAINTENANCE && !AllowIpsOnMaintenance()) {
            return response()->view('generals.salesend.end');
        } else {
            return response()->view('generals.items.list', compact(
                'conditions',
                'paginator',
                'root_item_categories',
                'item_category_id',
                'item_category',
                'current_mode',
                'updated_at'
            ));
        }
    }

    public function mode(
        ListPublishService $listPublishService,
        $condition = null){

        $current_mode = -1;

        $conditions = $listPublishService->conditionQueryToArray($condition);
        $publish = $listPublishService->list($conditions);

        $now_date = strtotime((new DateTime())->format('Y-m-d H:i:s'));
        $exhibit_date = 0;
        $sales_start_date = 0;
        $end_of_sale_date = 0;
        $emergency_flag = 0;
        if (count($publish) > 0) {
            $exhibit_date = strtotime($publish->first()->exhibit_date->format('Y-m-d H:i:s'));
            $sales_start_date = strtotime($publish->first()->sales_start_date->format('Y-m-d H:i:s'));
            $end_of_sale_date = strtotime($publish->first()->end_of_sale_date->format('Y-m-d H:i:s'));
            $emergency_flag = $publish->first()->emergency_flag;
        }
        if ($emergency_flag) {
            $current_mode = Modes::MAINTENANCE;
        } else {
            if ($exhibit_date > $now_date) {
                $current_mode = Modes::MAINTENANCE;
            }
            if ($exhibit_date < $now_date && $now_date < $end_of_sale_date) { // 閲覧モード-display mode 
                $current_mode = Modes::BROWSING;
            }
            if ($sales_start_date < $now_date && $now_date < $end_of_sale_date) { // 販売モード- sales mode
                $current_mode = Modes::SALES;
            }
            if ($end_of_sale_date < $now_date) { // メンテナンスモード- Maintenance mode
                $current_mode = Modes::MAINTENANCE;
            }
        }
        return $current_mode;
    }

    public function allList(
        ListItemsService $listItemsService,
        ListItemCategoriesService $listItemCategoriesService,
        ListPublishService $listPublishService,
        $condition = null
    ) {
        $item_category = null;
        $conditions = $listItemsService->conditionQueryToArray($condition);
        $conditions['is_visible'] = 1;

        if (!isset($conditions['count'])) {
            $conditions['count'] = ItemController::DEFAULT_COUNT;
        }

        if (!isset($conditions['sort'])) {
            $conditions['sort'] = ItemController::DEFAULT_SORT;
        }

        if (isset($conditions['item_category_id'])) {
            $item_category = $listItemCategoriesService->find((int)$conditions['item_category_id']);
        }

        $paginator = $listItemsService->paginate($conditions, (int)$conditions['count']);

        $item_category_id = isset($conditions['item_category_id'])?:1;
        $root_item_categories = $listItemCategoriesService->rootList();

        $item_categories = $listItemCategoriesService->list();
        $current_mode = $this->mode($listPublishService);
        $updated_at = 0;
        $publish_condition = $listPublishService->conditionQueryToArray($condition);
        $publish = $listPublishService->list($publish_condition);
        if (count($publish) > 0) {
            $updated_at = strtotime($publish->first()->updated_at->format('Y-m-d H:i:s'));
        }

        if ($current_mode == Modes::MAINTENANCE && !AllowIpsOnMaintenance()) {
            return response()->view('generals.salesend.end');
        } else {
            return response()->view('generals.items.list', compact(
                'conditions',
                'paginator',
                'root_item_categories',
                'item_category_id',
                'item_category',
                'item_categories',
                'current_mode',
                'updated_at'
            ));
        }
    }

    public function select(
        ListItemsService $listItemsService,
        Request $request
    ) {
        if ($request->input('maker_id')) {
            $request->merge([
                'maker_ids' => [$request->input('maker_id')]
            ]);
        }

        $condition = $listItemsService->conditionsToQuery([
            'name',
            'item_category_id',
            'sort',
            'count',
            'maker_ids',
            'self_medication',
            'price_id'
        ]);

        return redirect()->route('item.all', ['condition' => $condition]);
    }

    public function detail(
        ListItemsService $listItemsService,
        ListItemCategoriesService $listItemCategoriesService,
        ListPublishService $listPublishService,
        $item_category_id = null,
        Request $request,
        int $id,
        $condition = null
    ) {
        $conditions = [];

        if ($item_category_id != null) {
            $conditions['item_category_id'] = $item_category_id;
        }

        $item = $listItemsService->find($id);

        if(!$item->is_visible){
            return redirect()->route('home.index');
        }

        $cart = [];
        if($request->session()->has(ApiCartController::SESSION_KEY_SHOP_CART)){
            $cart = $request->session()->get(ApiCartController::SESSION_KEY_SHOP_CART, array());
        }

        $amount = 0;
        if(isset($cart[$item->id])){
            $amount = $cart[$item->id];
        }

        $min_amount = Item::MIN_AMOUNT;

        $root_item_categories = $listItemCategoriesService->rootList();

        $current_mode = $this->mode($listPublishService);
        $updated_at = 0;
        $publish_condition = $listPublishService->conditionQueryToArray($condition);
        $publish = $listPublishService->list($publish_condition);
        if (count($publish) > 0) {
            $updated_at = strtotime($publish->first()->updated_at->format('Y-m-d H:i:s'));
        }

        
        if ($current_mode == Modes::MAINTENANCE && !AllowIpsOnMaintenance()) {
            return response()->view('generals.salesend.end');
        } else {
            return response()->view('generals.items.detail', compact(
                'conditions',
                'item',
                'amount',
                'min_amount',
                'root_item_categories',
                'item_category_id',
                'current_mode',
                'updated_at'
            ));
        }
    }
}
