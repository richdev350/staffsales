<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Order\ListOrdersService;
use App\States\Payment\Paid;
use App\States\Payment\Cancel;
use Spatie\ModelStates\Exceptions\TransitionNotFound;
use App\Enums\Payment\Reason;

class OrderController extends Controller
{
    private function errorResponse($reason)
    {
        return response("NG");
    }

    /**
     * 注文内容を取得.
     */
    public function show(
        ListOrdersService $listOrdersService,
        Request $request
    ) {
        $order_id = $request->input('OrderNo');
        $ec_type = $request->input('EcType');
        $secure_code = $request->input('SecureCode');

        $order = $listOrdersService->findNoException((int)$order_id);

        if ($order) {
            if ("03" != $ec_type) {
                return $this->errorResponse(Reason::INVALID_SHOP);
            } else if ($order->secure_code != $secure_code) {
                return $this->errorResponse(Reason::INVALID_SECURE_CODE);
            } else {

                $order_info = [
                    "OrderNo" => $order_id,
                    "PaymentStatus" => "0",
                    "DeliveryStatus" => "0",
                    "CustomerName" => $order->name,
                    "StaffID" => sprintf("%08d", $order->staff_id),
                ];

                $item_details = [];
                foreach($order->order_details as $detail) {
                    $detail_row = [
                        "PluCode" => $detail->item->jan,
                        "ItemName" => $detail->item->name,
                        "Price" => "{$detail->price}",
                        "Qty" => sprintf("%02d", $detail->amount),
                    ];
                    array_push($item_details, $detail_row);
                }

                $order_info['itemDetails'] = $item_details;

                return response()->json($order_info);
            }
        } else {
            return $this->errorResponse(Reason::INVALID_ORDER_NO);
        }
    }
}
