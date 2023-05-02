<?php
declare(strict_types=1);

namespace App\Services\Item;

use Throwable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Entities\Item;
use App\Models\Repositories\Contracts\ItemRepositoryInterface;
use App\Services\Admin\AuthenticateItemService;

class DeleteItemService
{
    private $repository;
    private $request;

    public function __construct(ItemRepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request    = $request;
    }

    public function delete($inputs = null): Item
    {
        try {
            return DB::transaction(function () {
                $item = $this->repository->find((int) $this->request->offsetGet('id'));
                $this->repository->delete($item);

                return $item;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }

}
