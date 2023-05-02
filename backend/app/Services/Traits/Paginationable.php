<?php
declare(strict_types=1);

namespace App\Services\Traits;

use Illuminate\Pagination\LengthAwarePaginator;

/**
 * ページネーション用トレイト
 */
trait Paginationable
{
    /**
     * @param  array|null  $conditions
     * @return array
     */
    public function pagination(&$conditions): LengthAwarePaginator
    {
        if (is_null($conditions) || !isset($conditions['count'])) {
            $conditions['count'] = config('app.default_list_count');
        }

        return $this->paginate($conditions, (int)$conditions['count']);
    }

    /**
     * 指定条件に該当するモデルのページネーターを返す
     *
     */
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
