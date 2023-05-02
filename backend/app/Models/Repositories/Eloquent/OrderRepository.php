<?php
declare(strict_types=1);

namespace app\Models\Repositories\Eloquent;

use Throwable;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Entities\Order;
use App\Models\Repositories\Contracts\OrderRepositoryInterface;
use App\Models\Repositories\Eloquent\Repository;

final class OrderRepository extends Repository implements OrderRepositoryInterface
{
    protected static $model = Order::class;

    public function findBySecureCode(string $secure_code)
    {
        $query = Order::query();
        $query->where('secure_code', '=', $secure_code);

        return $query->first();
    }

    protected function buildWhereClauseByConditions(Builder &$queryBuilder, array $conditions = [])
    {
        parent::buildWhereClauseByConditions($queryBuilder, $conditions);

        $table = $queryBuilder->getModel()->getTable();

        if (array_key_exists('id', $conditions)) {
            $id = null === $conditions['id'] || '' === $conditions['id'] ? null : $conditions['id'];
            if (null !== $id) {
                $queryBuilder->where("{$table}.id", '=', $id);
            }
        }

        if (array_key_exists('name', $conditions)) {
            $name = null === $conditions['name'] || '' === $conditions['name'] ? null : (string) $conditions['name'];
            if (null !== $name) {
                $queryBuilder->where("{$table}.name", 'LIKE', $name . "%");
            }
        }

        if (array_key_exists('staff_id', $conditions)) {
            $staff_id = null === $conditions['staff_id'] || '' === $conditions['staff_id'] ? null : (string) $conditions['staff_id'];
            if (null !== $staff_id) {
                $queryBuilder->where("{$table}.staff_id", 'LIKE', "%" . $staff_id . "%");
            }
        }

        if (array_key_exists('created_at_from', $conditions)) {
            $created_at_from = null === $conditions['created_at_from'] || '' === $conditions['created_at_from'] ? null : (string) $conditions['created_at_from'];
            if (null !== $created_at_from) {
                $queryBuilder->where("{$table}.created_at", '>=', $created_at_from . ' 00:00:00');
            }
        }

        if (array_key_exists('created_at_to', $conditions)) {
            $created_at_to = null === $conditions['created_at_to'] || '' === $conditions['created_at_to'] ? null : (string) $conditions['created_at_to'];
            if (null !== $created_at_to) {
                $queryBuilder->where("{$table}.created_at", '<=', $created_at_to . ' 23:59:59');
            }
        }

    }
}
