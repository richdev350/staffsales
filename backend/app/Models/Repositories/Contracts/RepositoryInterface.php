<?php
declare(strict_types=1);

namespace App\Models\Repositories\Contracts;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model as Entity;

interface RepositoryInterface
{
    /**
     * 配列からモデルを作成して返す
     *
     * @param  array  $data
     * @return Entity
     */
    public function new(array $data): Entity;

    /**
     * 指定IDのモデルを取得して返す
     *
     * @param  int  $id
     * @return Entity
     */
    public function find(int $id): Entity;

    /**
     * 配列からモデルを編集して返す
     *
     * @param  Entity  $entity
     * @param  array   $data
     * @return Entity
     */
    public function edit(Entity $entity, array $data): Entity;

    /**
     * モデルを永続化して返す
     *
     * @param  Entity  $entity
     * @return Entity
     */
    public function persist(Entity $entity): Entity;

    /**
     * モデルを削除する
     *
     * @param  Entity  $entity
     * @return bool|null  永続化済みモデルじゃなかった場合はnullを返す
     */
    public function delete(Entity $entity);

    /**
     * 指定条件に該当するデータ件数を返す
     *
     * @param  array  $conditions
     * @return int
     */
    public function count(array $conditions = []): int;

    /**
     * 指定条件に該当するモデルのコレクションを返す
     *
     * @param  array  $conditions
     * @param  int    $limit
     * @param  int    $offset
     * @return Collection
     */
    public function list(array $conditions = [], $limit = null, $offset = null): Collection;

    /**
     * 指定条件に該当するモデルのページネーターを返す
     *
     * @param  array  $conditions
     * @param  int    $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(array $conditions = [], int $perPage): LengthAwarePaginator;
}
