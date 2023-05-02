<?php
declare(strict_types=1);

namespace App\Services\Order;

use Throwable;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Application;
use App\Exceptions\ImageExifException;
use App\Models\Entities\Order;
use App\Models\Entities\OrderDetail;
use App\Models\Repositories\Contracts\OrderRepositoryInterface;
use App\Http\Requests\Order\SaveOrderRequest;
use App\Http\Requests\Order\SaveOrderRequestFilter;
use App\Services\Traits\Filterable;
use App\Services\Traits\Validatable;
use Illuminate\Support\Collection;
use App\States\Payment;

class UpdateOrderService
{
    use Filterable,
        Validatable;

    private $repository;

    private $request;

    public function __construct(
        OrderRepositoryInterface $repository,
        Request $request
    ) {
        $this->repository        = $repository;
        $this->request           = $request;

        $this->setRequestFilter(new SaveOrderRequestFilter());
        $this->setFormRequest(new SaveOrderRequest());
        $this->init();
    }

    public function init()
    {
        $order = $this->repository->findWithTrashed((int) $this->request->offsetGet('id'));
        if (! $this->request->isMethod('GET')) {
            $defaults = [
                'details'           => $order->order_details,
                'sum'               => $order->sum,
            ];
            $this->request->merge($defaults);
            $this->filterInputs();
            return;
        }

        $this->request->flush();

        $order = $this->repository->findWithTrashed((int) $this->request->offsetGet('id'));

        $defaults = [
            'id'                   => $order->id,
            'name'                 => $order->name,
            'staff_id'             => $order->staff_id,
            'details'              => $order->order_details,
            'sum'                  => $order->sum,
            'secure_code'          => $order->secure_code,
            'created_at'           => $order->created_at,
            'deleted_at'           => $order->deleted_at,
        ];
        $this->request->merge($defaults);
    }

    public function update($inputs = null): Order
    {
        if (is_null($inputs)) {
            $inputs = $this->request->except('action');
        }
        try {
            // DatePickerの日付を変換
            return DB::transaction(function () use ($inputs) {
                if ($this->request->offsetExists('id')) {
                    $order = $this->repository->findWithTrashed((int) $this->request->offsetGet('id'));
                }
                if(isset($inputs['created_at'])){
                    unset($inputs['created_at']);
                }
                $order = $this->repository->edit($order, $inputs);

                $order = $this->repository->persist($order);

                return $order;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}
