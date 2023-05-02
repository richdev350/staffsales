<?php
declare(strict_types=1);

namespace App\Services\ItemCategory;

use Throwable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Entities\ItemCategory;
use App\Models\Repositories\Contracts\ItemCategoryRepositoryInterface;
use App\Services\Admin\AuthenticateItemCategoryService;

class DeleteItemCategoryService
{
    private $repository;
    private $request;

    public function __construct(ItemCategoryRepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request    = $request;
    }

    public function delete($inputs = null): ItemCategory
    {
        try {
            return DB::transaction(function () {
                $item_category = $this->repository->find((int) $this->request->offsetGet('id'));
                $this->repository->delete($item_category);
                return $item_category;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}
