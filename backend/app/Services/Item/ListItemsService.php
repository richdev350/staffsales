<?php
declare(strict_types=1);

namespace App\Services\Item;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Entities\Item;
use App\Models\Repositories\Contracts\ItemRepositoryInterface;
use App\Services\Traits\Conditionable;
use App\Services\Traits\Paginationable;

class ListItemsService
{
    use Conditionable,
        Paginationable;

    private $repository;
    private $request;

    public function __construct(ItemRepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request    = $request;
    }

    private function _get_order($conditions = null) {
        $order = [];

        if(! isset($conditions['orders']) && ! isset($conditions['orderby']) && ! isset($conditions['orderByRaws'])){
            $order = [
                'id' => 'ASC',
            ];
        }

        if (isset($conditions['sort'])) {
            $sort = $conditions['sort'];
            switch ($sort) {
                case "sort_no":
                case "created_desc":
                    $order = [
                        'created_at' => 'desc',
                    ];
                    break;

                case "price_asc":
                    $order = [
                        'price' => 'ASC',
                    ];
                    break;
                case "price_desc":
                    $order = [
                        'price' => 'desc',
                    ];
                    break;
                //TODO: Check if the below logics are correct
                case "quantity":
                    $order = [
                        'sale_count' => 'DESC',
                    ];
                    break;
                case "sales":
                    $order = [
                        'sale_amount' => 'DESC',
                    ];
                    break;

            }
        }

        return $order;
    }

    public function list($conditions = null, $limit = null, $offset = null): Collection
    {
        if (! is_array($conditions)) {
            $conditions = $this->conditionQueryToArray($conditions);
        }

        if(isset($conditions['name'])){
            $conditions['search'] = $conditions['name'];
            unset($conditions['name']);
        }
        
        $orders = $this->_get_order($conditions);
        if (!empty($orders)) {
            $conditions['orders'] = $orders;
        }

        return $this->repository->list($conditions, $limit, $offset);
    }

    public function paginate($conditions = null, int $perPage = 10): LengthAwarePaginator
    {
        if (! is_array($conditions)) {
            $conditions = $this->conditionQueryToArray($conditions);
        }

        if(isset($conditions['name'])){
            $conditions['search'] = $conditions['name'];
            unset($conditions['name']);
        }
        
        $orders = $this->_get_order($conditions);
        if (!empty($orders)) {
            $conditions['orders'] = $orders;
        }

        return $this->repository->paginate($conditions, $perPage);
    }

    public function find($id): Item
    {
        return $this->repository->find($id);
    }
}
