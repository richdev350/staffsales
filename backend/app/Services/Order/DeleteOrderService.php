<?php
declare(strict_types=1);

namespace App\Services\Order;

use Throwable;
use Exception;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Entities\Order;
use App\Models\Repositories\Contracts\OrderRepositoryInterface;
use App\Services\Admin\AuthenticateShopService;

class DeleteOrderService
{
    private $repository;
    private $request;

    public function __construct(OrderRepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request    = $request;
    }

    public function delete($inputs = null): Order
    {
        try {
            return DB::transaction(function () {
                $order = $this->repository->find((int) $this->request->offsetGet('id'));
                $app = Application::getInstance();
                if($app->login_admin_user->can('admin_permission') || $order->state == 'pending'){
                    $this->repository->delete($order);
                    return $this->repository->findOnlyTrashed((int) $this->request->offsetGet('id'));
                }
                return $order;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}
