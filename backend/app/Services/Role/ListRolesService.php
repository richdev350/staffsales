<?php
declare(strict_types=1);

namespace App\Services\Role;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Entities\Role;
use App\Models\Repositories\Contracts\RoleRepositoryInterface;
use App\Services\Traits\Conditionable;

/**
 * ロール一覧サービス
 */
class ListRolesService
{
    use Conditionable;

    /**
     * @var RoleRepositoryInterface
     */
    private $repository;

    /**
     * @var Request
     */
    private $request;

    /**
     * コンストラクタ
     *
     */
    public function __construct(RoleRepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request    = $request;
    }

    /**
     * 指定条件に該当するモデルのコレクションを返す
     *
     */
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
