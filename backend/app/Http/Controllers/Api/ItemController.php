<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Log;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Http\Controllers\Controller;
use App\Models\Entities\Item;
use App\Services\ItemCategory\ListItemCategoriesService;
use App\Services\Item\ListItemsService;
use App\Exceptions\ImportValidationException;
use App\Services\Publish\ListPublishService;
use DateTime;
use App\Enums\Mode\Modes;


class ItemController extends Controller
{
    const LIMIT = 10;

    public function search(
        ListItemsService $listItemsService,
        Request $request
    ) {
        $conditions = [
            'name' => $request->input('name'),
            'item_category_id' => $request->input('item_category_id'),
            'maker_ids' => $request->input('maker_ids'),
            'labels' => $request->input('labels'),
            'label' => $request->input('label'),
            'maker_id' => $request->input('maker_id'),
            'price_id' => $request->input('price_id'),
            'orders' => ['name' => 'ASC'],
            'is_visible' => 1,
        ];
        
        $items = $listItemsService->list($conditions, self::LIMIT);

        $items = $items->map(function($item) {
            return [
                "id" => $item->id,
                "label" => $item->name,
                "value" => $item->name,
            ];
        });

        return response()->json($items);

    }

    public function publish(
        ListPublishService $listPublishService,
        $condition = null){
            $conditions = $listPublishService->conditionQueryToArray($condition);
            $publish = $listPublishService->list($conditions);
            $refresh = 0;
            $now_date = strtotime((new DateTime())->format('Y-m-d H:i:s'));
            $exhibit_date = 0;
            $sales_start_date = 0;
            $end_of_sale_date = 0;
            $updated_at = 0;

            if (count($publish) > 0) {
                $exhibit_date = strtotime($publish->first()->exhibit_date->format('Y-m-d H:i:s'));
                $sales_start_date = strtotime($publish->first()->sales_start_date->format('Y-m-d H:i:s'));
                $end_of_sale_date = strtotime($publish->first()->end_of_sale_date->format('Y-m-d H:i:s'));
                $updated_at = strtotime($publish->first()->updated_at->format('Y-m-d H:i:s'));
            }
            if ($now_date == $exhibit_date) {
                $refresh = 1;
            } elseif ($now_date == $sales_start_date) {
                $refresh = 2;
            } elseif ($now_date == $end_of_sale_date) {
                $refresh = 3;
            } else {
                $refresh = 0;
            }
            $data = array(
                'res' => $refresh,
                'updated_at' => $updated_at
            );
        return $data;
    }
}
