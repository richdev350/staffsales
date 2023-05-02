<?php
declare(strict_types=1);

namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\CartController as ApiCartController;
use App\Models\Entities\Item;
use App\Models\Entities\Cart;
use App\Services\Cart\ListCartsService;
use App\Services\ItemCategory\ListItemCategoriesService;
use App\Services\Publish\ListPublishService;
use DateTime;
use App\Enums\Mode\Modes;
use App\Services\Barcode\ListBarcodeService;

class CartController extends BaseGeneralController
{
    public function list(
        ListCartsService $listCartsService,
        ListItemCategoriesService $listItemCategoriesService,
        Request $request,
        ListPublishService $listPublishService,
        ListBarcodeService $listBarcodeService,
        $condition = null
    ) {
        $messages = [];
        $errors = new MessageBag;

        $cart = [];
        if($request->session()->has(ApiCartController::SESSION_KEY_SHOP_CART)){
            $cart = $request->session()->get(ApiCartController::SESSION_KEY_SHOP_CART, array());
        }

        $cart_items = $listCartsService->getCartItems($cart);

        $request->session()->put(ApiCartController::SESSION_KEY_SHOP_CART, $cart);

        $min_amount = Item::MIN_AMOUNT;

        $root_item_categories = $listItemCategoriesService->rootList();

        $current_mode = $this->mode($listPublishService);
        $conditions = $listPublishService->conditionQueryToArray($condition);
        $publish = $listPublishService->list($conditions);
        $updated_at = 0;
        if (count($publish) > 0) {
            $updated_at = strtotime($publish->first()->updated_at->format('Y-m-d H:i:s'));
        }

        $barcode_status = $listBarcodeService->isLimited();

        if ($current_mode == Modes::MAINTENANCE && !AllowIpsOnMaintenance()) {
            return response()->view('generals.salesend.end');
        } else {
            return response()->view('generals.carts.list', compact(
                'cart_items',
                'min_amount',
                'root_item_categories',
                'current_mode',
                'updated_at',
                'barcode_status'
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
}
