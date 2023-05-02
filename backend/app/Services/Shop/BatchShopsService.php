<?php
declare(strict_types=1);

namespace App\Services\Shop;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Models\Entities\Shop;
use App\Models\Repositories\Contracts\ShopRepositoryInterface;
use App\Services\Traits\Conditionable;

class BatchShopsService
{
    private $repository;
    private $request;

    public function __construct(ShopRepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request    = $request;
    }

    public function batch(): int
    {
        $targetIds = array_map('intval', explode(',', $this->request->input('targets', '')));
        if (0 == count($targetIds)) {
            return 0;
        }

        try {
            return DB::transaction(function () use ($targetIds) {
                $collection = $this->repository->list(['ids' => $targetIds]);
                $count      = $collection->count();

                switch ($this->request->input('action')) {
                    case 'delete':
                        foreach ($collection as $admn_user) {
                            $this->repository->delete($admn_user);
                        }
                        break;
                    default:
                        break;
                }

                return $count;
            });
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
