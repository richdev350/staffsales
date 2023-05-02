<?php
declare(strict_types=1);

namespace App\Models\Repositories\Eloquent;

use Throwable;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Entities\Maker;
use App\Models\Repositories\Contracts\MakerRepositoryInterface;
use App\Models\Repositories\Eloquent\Repository;

final class MakerRepository extends Repository implements MakerRepositoryInterface
{
    protected static $model = Maker::class;

    protected function buildWhereClauseByConditions(Builder &$queryBuilder, array $conditions = [])
    {
        parent::buildWhereClauseByConditions($queryBuilder, $conditions);

        $table = $queryBuilder->getModel()->getTable();

        if (array_key_exists('name', $conditions)) {
            $name = null === $conditions['name'] || '' === $conditions['name'] ? null : (string) $conditions['name'];
            if (null !== $name) {
                $queryBuilder->where("{$table}.name", 'LIKE', $name . "%");
            }
        }
    }
}
