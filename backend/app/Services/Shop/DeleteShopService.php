<?php
declare(strict_types=1);

namespace App\Services\Shop;

use Throwable;
use Exception;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Entities\Shop;
use App\Models\Repositories\Contracts\ShopRepositoryInterface;
use App\Services\Admin\AuthenticateShopService;

class DeleteShopService
{
    private $repository;
    private $request;

    public function __construct(ShopRepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request    = $request;
    }

    public function delete($inputs = null): Shop
    {
        try {
            return DB::transaction(function () {
                $shop = $this->repository->find((int) $this->request->offsetGet('id'));
                $this->repository->delete($shop);

                return $shop;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}
