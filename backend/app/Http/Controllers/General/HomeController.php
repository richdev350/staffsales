<?php
declare(strict_types=1);

namespace App\Http\Controllers\General;

use DateTime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Barcode\DeleteBarcodeService;
use App\Services\Barcode\ListBarcodeService;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
use App\Services\Publish\ListPublishService;
use App\Services\ItemCategory\ListItemCategoriesService;
use App\Enums\Mode\Modes;

class HomeController extends BaseGeneralController
{
    public function index(
        ListItemCategoriesService $listItemCategoriesService,
        ListBarcodeService $listBarcodeService,
        ListPublishService $listPublishService
    ) {
        $root_item_categories = $listItemCategoriesService->rootList();
        $barcode_datas = $listBarcodeService->list();
        $current_mode = $this->mode($listPublishService);

        return response()->view('generals.home.index', compact(
            'root_item_categories',
            'barcode_datas',
            'current_mode'
        ));

        // return Redirect::route('item.all');
    }

    public function list(
        $condition = null,
        ListPublishService $listPublishService
    ) {
        $conditions = $listPublishService->conditionQueryToArray($condition);
        $publish = $listPublishService->list($conditions);
        $is_end_of_sale_date_visible = 0;
        $sales_start_date = '';
        $end_of_sale_date = '';
        $current_mode = $this->mode($listPublishService);

        if (count($publish) > 0) {
            $is_end_of_sale_date_visible = $publish->first()->is_end_of_sale_date_visible;
        }

        if ($is_end_of_sale_date_visible === 1) {
            if (count($publish) > 0) {
                $sales_start_date = $publish->first()->sales_start_date->format('Y-m-d H:i');
                $end_of_sale_date = $publish->first()->end_of_sale_date->format('Y-m-d H:i');
            }
        } else {
            if (count($publish) > 0) {
                $sales_start_date = $publish->first()->sales_start_date->format('Y-m-d H:i');
                $end_of_sale_date = '';
            }
        }
        
        $updated_at = 0;
        if (count($publish) > 0) {
            $name = $publish->first()->name;
            $updated_at = strtotime($publish->first()->updated_at->format('Y-m-d H:i:s'));
        }

        return response()->view('generals.home.top', compact(
            'sales_start_date',
            'end_of_sale_date',
            'updated_at',
            'name',
            'current_mode'
        ));
    }

    public function destroyBarcode(
        $barcode,
        DeleteBarcodeService $deleteBarcodeService
    ) {
        $deleteBarcodeService->delete($barcode);

        return back();
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
