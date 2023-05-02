<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Http\Controllers\Controller;
use App\Models\Entities\Order;
use App\Models\Entities\DesiredTime;
use App\Services\Shop\ListShopsService;
use App\Services\Order\ListOrdersService;
use App\Services\Order\ExportOrdersService;
use App\Services\Order\UpdateOrderService;
use App\Services\Order\DeleteOrderService;
use App\Services\DesiredTime\ListDesiredTimesService;
use App\States\Payment\PaymentState;
use App\Exports\OrdersExport;

class OrderController extends Controller
{
    public function list(
        ListOrdersService $listOrdersService,
        ListShopsService $listShopsService,
        ListDesiredTimesService $listDesiredTimesService,
        $condition = null
    ) {
        $conditions = $listOrdersService->conditionQueryToArray($condition);
        if(isset($conditions['created_at_from']) && isset($conditions['created_at_to']) && $conditions['created_at_from'] > $conditions['created_at_to']){
            $created_at_from = $conditions['created_at_to'];
            $conditions['created_at_to'] = $conditions['created_at_from'];
            $conditions['created_at_from'] = $created_at_from;
        }

        $paginator = $listOrdersService->pagination($conditions);

        return response()->view('admins.orders.list', compact(
            'conditions',
            'paginator',
        ));
    }

    public function select(
        ListOrdersService $listOrdersService,
        Request $request
    )
    {
        $condition = $listOrdersService->conditionsToQuery([
            'id',
            'name',
            'staff_id',
            'created_at_from',
            'created_at_to',
            'only_trashed',
        ]);

        if($request->input('csv_download')){
            return redirect()->route('admin.order.export', ['condition' => $condition]);
        } else {
            return redirect()->route('admin.order.list', ['condition' => $condition]);
        }
    }

    public function show(
        UpdateOrderService $updateOrderService,
        Request $request,
        int $id
    ) {

        $request->flash();

        return response()->view('admins.orders.show', compact(
            'id'
        ));
    }

    public function edit(
        UpdateOrderService $updateOrderService,
        ListDesiredTimesService $listDesiredTimesService,
        Request $request,
        int $id
    ) {
        $request->flash();

        return response()->view('admins.orders.edit', compact(
            'id',
        ));
    }

    public function update(
        UpdateOrderService $updateOrderService,
        ListDesiredTimesService $listDesiredTimesService,
        Request $request,
        int $id
    ) {
        $errors = new MessageBag;

        $action = $request->input('action');
        if (false === array_search($action, ['confirm', 'save', 'return'])) {
            abort(500, sprintf('Invalid action. action: %s', $action));
        }

        $viewPath = 'admins.orders.edit';
        if ($updateOrderService->passesValidation()) {
            if ('confirm' === $action) {
                $viewPath = 'admins.orders.confirm';
            } elseif ('save' === $action) {
                try {
                    $order = $updateOrderService->update();
                    $request->session()->flash('message', '受注を保存しました。');
                } catch (Throwable $exception) {
                    throw $exception;
                }

                return redirect()->route('admin.order.list');
            }
        } else {
            $errors = $updateOrderService->getValidationMessages();
        }

        $request->flash();

        return response()->view($viewPath, compact(
            'id',
            'errors',
        ));
    }

    public function destroy(
        DeleteOrderService $deleteOrderService,
        Request $request,
        int $id
    ) {
        try {
            $order = $deleteOrderService->delete();

            if($order->deleted_at){
                $request->session()->flash('message', sprintf('受注「%s」を削除しました。', $order->id));
            }else{
                $request->session()->flash('message', sprintf('受注「%s」は削除出来ません。', $order->id));
            }

        } catch (Throwable $exception) {
            throw $exception;
        }

        return redirect()->route('admin.order.list');
    }

    public function export(
        ListOrdersService $listOrdersService,
        $condition = null
    ) {
        $conditions = $listOrdersService->conditionQueryToArray($condition);
        $orders = $listOrdersService->list($conditions);
        
        return response()->streamDownload(
            function () use($orders) {
                // 出力バッファをopen
                $stream = fopen('php://output', 'w');
                // ヘッダー
                fputcsv($stream, $this->csv_headings());
                // データ
                foreach($orders as $order){
                    $order_rows = $this->csv_map($order);
                    foreach($order_rows as $order_row){
                        fputcsv($stream, $order_row);
                    }
                }
                fclose($stream);
            },
            'orders.csv',
            [
                'Content-Type' => 'application/octet-stream',
            ]
        );
        //return new OrdersExport($listOrdersService, $condition);
    }

    public function csv_headings(): array
    {
        return [
            '注文番号',
            '注文日時',
            '名前',
            '社員番号',
            '購入金額',
            'JAN',
            '商品名',
            '数量',
            '単価',
            '小計',
        ];
    }

    public function csv_map($order): array
    {
        $result_rows = [];
        $empty_value = null;
        $index = 0;
        foreach($order->order_details as $detail) {
            $row = [
                $index==0?$order->id:$empty_value,
                $index==0?$order->created_at:$empty_value,
                $index==0?$order->name:$empty_value,
                $index==0?$order->staff_id:$empty_value,
                $index==0?$order->sum:$empty_value,
                $detail->item->jan,
                $detail->item->name,
                $detail->amount,
                $detail->price,
                $detail->amount * $detail->price,
            ];
            array_push($result_rows, $row);
            $index++;
        }

        return $result_rows;
    }
}
