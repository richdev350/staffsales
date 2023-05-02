<?php
declare(strict_types=1);

namespace App\Http\Controllers\General;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Http\Controllers\Controller;
use App\Models\Entities\Order;
use App\Models\Entities\Shop;
use App\Services\Barcode\CreateBarcodeService;
use App\Services\Order\ListOrdersService;
use App\Services\Order\CreateOrderService;
use App\Services\DesiredTime\ListDesiredTimesService;
use App\Services\Item\ListItemsService;
use App\Services\Publish\ListPublishService;
use App\Enums\Mode\Modes;
use DateTime;

class OrderController extends BaseGeneralController
{
    public function create(
        CreateOrderService $createOrderService,
        ListPublishService $listPublishService,
        ListDesiredTimesService $listDesiredTimesService,
        Request $request
    ) {
        if(!$request->session()->has('shop_cart') || !$request->session()->get('shop_cart')){
            return redirect()->route('home.index');
        }
        $desired_times = $listDesiredTimesService->list();
        $current_mode = $this->mode($listPublishService);
        $request->flash();
        if ($current_mode == Modes::MAINTENANCE && !AllowIpsOnMaintenance()) {
            return response()->view('generals.salesend.end');
        } else {
        return response()->view('generals.orders.form', compact(
            'desired_times',
            'current_mode'
        ));
        }
    }

    public function store(
        CreateOrderService $createOrderService,
        CreateBarcodeService $createBarcodeService,
        ListDesiredTimesService $listDesiredTimesService,
        ListItemsService $listItemsService,
        ListPublishService $listPublishService,
        Request $request
    )
    {
        if(!$request->session()->has('shop_cart') || !$request->session()->get('shop_cart')){
            return redirect()->route('home.index');
        }
        $errors = new MessageBag;
        $order_details = [];
        $sum = 0;
        $current_mode = $this->mode($listPublishService);

        $action = $request->input('action');
        if (false === array_search($action, ['confirm', 'save', 'return'])) {
            abort(500, sprintf('Invalid action. action: %s', $action));
        }

        $viewPath = 'generals.orders.form';
        if ($createOrderService->passesValidation()) {
            if ('confirm' === $action) {
                $viewPath = 'generals.orders.confirm';

                $inputs = $request->except('action');
                $request->session()->put('shop_form', $inputs);

                $detail_rows = $request->session()->get('shop_cart');

                if(isset($detail_rows) && count($detail_rows)){
                    foreach($detail_rows as $item_id => $amount){
                        $item = $listItemsService->find($item_id);
                        $order_details[] = [
                            'item' => $item,
                            'amount' => $amount,
                        ];
                        $sum += $item->price * $amount;
                    }
                }
            } elseif ('save' === $action) {
                try {
                    $order = $createOrderService->create();
                    $createBarcodeService->create($order);
                } catch (Throwable $exception) {
                    throw $exception;
                }
                $request->session()->forget('shop_cart');
                $request->session()->forget('shop_form');
                $request->session()->put('shop_order_id', $order->id);
                if ($current_mode == Modes::MAINTENANCE && !AllowIpsOnMaintenance()) {
                    return response()->view('generals.salesend.end');
                } else {
                return redirect()->route('order.thanks', compact(
                    'current_mode'));
                }
            }
        } else {
            $errors = $createOrderService->getValidationMessages();
        }
        $desired_times = $listDesiredTimesService->list();

        $request->flash();
        if ($current_mode == Modes::MAINTENANCE && !AllowIpsOnMaintenance()) {
            return response()->view('generals.salesend.end');
        } else {
        return response()->view($viewPath, compact(
            'errors',
            'order_details',
            'sum',
            'desired_times',
            'current_mode'
        ));
        }
    }


    public function thanks(
        ListOrdersService $listOrdersService,
        ListPublishService $listPublishService,
        Request $request
    ) {
        if($request->session()->has('shop_order_id')){
            $order_id = $request->session()->get('shop_order_id');
        }else{
            return redirect()->route('item.list', ['item_category_id' => 1]);
        }
        $order = $listOrdersService->find($order_id);
        $current_mode = $this->mode($listPublishService);

        $request->flash();
        if ($current_mode == Modes::MAINTENANCE && !AllowIpsOnMaintenance()) {
            return response()->view('generals.salesend.end');
        } else {
        return response()->view('generals.orders.thanks', compact(
            'order',
            'current_mode'
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
