<?php
declare(strict_types=1);

namespace App\Services\Order;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Entities\Order;
use App\Models\Repositories\Contracts\OrderRepositoryInterface;
use App\Services\Traits\Conditionable;
use App\Services\Traits\Paginationable;

class ListOrdersService
{
    use Conditionable,
        Paginationable;

    private $repository;
    private $request;

    public function __construct(OrderRepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request    = $request;
    }

    public function find($id): Order
    {
        return $this->repository->find($id);
    }

    public function findNoException($id)
    {
        return $this->repository->findNoException($id);
    }

    public function list($conditions = null, $limit = null, $offset = null): Collection
    {
        if (! is_array($conditions)) {
            $conditions = $this->conditionQueryToArray($conditions);
        }
        if(! isset($conditions['orders']) && ! isset($conditions['orderby']) && ! isset($conditions['orderByRaws'])){
            $conditions['orders'] = [
                'id' => 'ASC',
            ];
        }

        return $this->repository->list($conditions, $limit, $offset);
    }

    public function paginate($conditions = null, int $perPage = 10): LengthAwarePaginator
    {
        if (! is_array($conditions)) {
            $conditions = $this->conditionQueryToArray($conditions);
        }
        if(! isset($conditions['orders']) && ! isset($conditions['orderby']) && ! isset($conditions['orderByRaws'])){
            $conditions['orders'] = [
                'id' => 'ASC',
            ];
        }

        return $this->repository->paginate($conditions, $perPage);
    }
}
