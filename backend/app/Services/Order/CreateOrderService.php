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
use App\Models\Repositories\Contracts\ItemRepositoryInterface;
use App\Models\Repositories\Contracts\OrderRepositoryInterface;
use App\Models\Repositories\Contracts\OrderDetailRepositoryInterface;
use App\Http\Requests\Order\SaveOrderRequest;
use App\Http\Requests\Order\SaveOrderRequestFilter;
use App\Services\Admin\AuthenticateAdminUserService;
use App\Services\Traits\Filterable;
use App\Services\Traits\Validatable;
use Illuminate\Support\Collection;

class CreateOrderService
{
    use Filterable,
        Validatable;

    private $repository;
    private $detailRrepository;
    private $itemRepository;

    private $request;

    public function __construct(
        OrderRepositoryInterface $repository,
        OrderDetailRepositoryInterface $detailRepository,
        ItemRepositoryInterface $itemRepository,
        Request $request
    ) {
        $this->repository       = $repository;
        $this->detailRepository = $detailRepository;
        $this->itemRepository   = $itemRepository;
        $this->request          = $request;

        $this->setRequestFilter(new SaveOrderRequestFilter());
        $this->setFormRequest(new SaveOrderRequest());
        $this->init();
    }

    public function init()
    {
        if (! $this->request->isMethod('GET')) {
            $this->filterInputs();
            return;
        }

        $this->request->flush();

        $inputs = $this->request->session()->get('shop_form');
        $defaults = [];
        if($inputs){
            foreach($inputs as $name => $value){
                $defaults[$name] = $value;
            }
        }
        $this->request->merge($defaults);

    }

    public function create($inputs = null): Order
    {
        if (is_null($inputs)) {
            $inputs = $this->request->except('action');
        }
        try {
            return DB::transaction(function () use ($inputs) {

                $inputs['sum'] = 0;
                do{
                    $secure_code = sprintf("%08d", mt_rand(1,99999999));
                    if(!$this->repository->findBySecureCode($secure_code)){
                        break;
                    }
                }while(0);
                $inputs['secure_code'] = $secure_code;
                $order = $this->repository->new($inputs);

                $order = $this->repository->persist($order);

                $detail_rows = $this->request->session()->get('shop_cart');
                $sum = 0;

                if(isset($detail_rows) && count($detail_rows)){
                    foreach($detail_rows as $item_id => $amount){
                        $item = $this->itemRepository->find($item_id);
                        $order_detail = [
                            'order_id' => $order->id,
                            'item_id' => $item->id,
                            'amount' => $amount,
                            'price' => $item->price,
                        ];
                        $order_detail = $this->detailRepository->new($order_detail);
                        $order_detail = $this->detailRepository->persist($order_detail);
                        $sum += $item->price * $amount;
                    }
                }
                // 合計の更新
                $order->sum = $sum;
                $order = $this->repository->persist($order);

                return $order;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}
