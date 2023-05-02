<?php
declare(strict_types=1);

namespace App\Models\Repositories\Eloquent;

use Throwable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model as Entity;
use App\Models\Repositories\Contracts\RepositoryInterface;

abstract class Repository implements RepositoryInterface
{
    /**
     * モデル
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected static $model;

    /**
     * 抽出時のデフォルトの表示順
     *
     * @var array
     */
    protected static $defaultOrders = [
        'id' => 'DESC',
    ];

    /**
     * Eager Loading
     *
     * @var array
     */
    protected static $eagerLoadings = [];

    /**
     * 配列からモデルを作成して返す
     *
     * @param  array  $data
     * @return Entity
     */
    public function new(array $data): Entity
    {
        return new static::$model($data);
    }

    /**
     * 指定IDのモデルを取得して返す
     *
     * @param  int  $id
     * @return Entity
     */
    public function find(int $id): Entity
    {
        return (static::$model)::findOrFail($id);
    }

    /**
     * 指定IDのモデルを取得して返す ※例外エラーなし
     *
     * @param  int  $id
     * @return Entity or null
     */
    public function findNoException(int $id)
    {
        return (static::$model)::find($id);
    }

    /**
     * 指定IDの削除済みモデルを取得して返す
     *
     * @param  int  $id
     * @return Entity
     */
    public function findOnlyTrashed(int $id): Entity
    {
        return (static::$model)::onlyTrashed()->findOrFail($id);
    }

    /**
     * 指定IDの削除済みを含むモデルを取得して返す
     *
     * @param  int  $id
     * @return Entity
     */
    public function findWithTrashed(int $id): Entity
    {
        return (static::$model)::withTrashed()->findOrFail($id);
    }

    /**
     * 配列からモデルを編集して返す
     *
     * @param  Entity  $entity
     * @param  array   $data
     * @return Entity
     */
    public function edit(Entity $entity, array $data): Entity
    {
        $entity->fill($data);

        return $entity;
    }

    /**
     * モデルを永続化して返す
     *
     * @param  Entity  $entity
     * @return Entity
     * @throws Throwable
     */
    public function persist(Entity $entity): Entity
    {
        try {
            return DB::transaction(function () use ($entity) {
                $entity->saveOrFail();

                return $entity;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }

    /**
     * モデルを削除する
     *
     * @param  Entity  $entity
     * @return bool|null  永続化済みモデルじゃなかった場合はnullを返す
     * @throws Throwable
     */
    public function delete(Entity $entity)
    {
        try {
            return DB::transaction(function () use ($entity) {
                $result = $entity->delete();

                return $result;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }

    /**
     * モデルを削除から復旧する
     *
     * @param  Entity  $entity
     * @return bool|null  永続化済みモデルじゃなかった場合はnullを返す
     * @throws Throwable
     */
    public function restore(Entity $entity)
    {
        try {
            return DB::transaction(function () use ($entity) {
                $result = $entity->restore();

                return $result;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }

    /**
     * 指定条件に該当するデータ件数を返す
     *
     * @param  array  $conditions
     * @return int
     */
    public function count(array $conditions = []): int
    {
        $query = (static::$model)::query();

        $table = $query->getModel()->getTable();

        $query->selectRaw("COUNT(`{$table}`.`id`) as `count`");
        $this->buildWhereClauseByConditions($query, $conditions);

        return $query->count();
    }

    /**
     * 指定条件に該当するモデルのコレクションを返す
     *
     * @param  array  $conditions
     * @param  int    $limit
     * @param  int    $offset
     * @return Collection
     */
    public function list(array $conditions = [], $limit = null, $offset = null): Collection
    {
        $query = (static::$model)::query();

        $table = $query->getModel()->getTable();

        if (0 < count(static::$eagerLoadings)) {
            $query->with(static::$eagerLoadings);
        }

        $this->buildWhereClauseByConditions($query, $conditions);
        $this->buildOrderbyClauseByConditions($query, $conditions);

        if (null != $limit) {
            $query->limit($limit);
        }
        if (null != $offset) {
            $query->offset($offset);
        }

        if(isset($conditions['only_trashed'])){
            $collection  = $query->onlyTrashed()->get();
        }elseif(isset($conditions['with_trashed'])){
            $collection  = $query->withTrashed()->get();
        }else{
            $collection  = $query->get();
        }

        return collect($collection)->map(function (Entity $entity) {
            return $entity;
        });
    }

    /**
     * 指定条件に該当するモデルのページネーターを返す
     *
     * @param  array  $conditions
     * @param  int    $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(array $conditions = [], int $perPage): LengthAwarePaginator
    {
        $query = (static::$model)::query();

        $table = $query->getModel()->getTable();

        if (0 < count(static::$eagerLoadings)) {
            $query->with(static::$eagerLoadings);
        }

        $this->buildWhereClauseByConditions($query, $conditions);
        $this->buildOrderbyClauseByConditions($query, $conditions);
        if(isset($conditions['only_trashed'])){
            $paginator  = $query->onlyTrashed()->paginate($perPage);
        }elseif(isset($conditions['with_trashed'])){
            $paginator  = $query->withTrashed()->paginate($perPage);
        }else{
            $paginator  = $query->paginate($perPage);
        }
        $collection = $paginator->getCollection();

        return $paginator->setCollection(collect($collection)->map(function (Entity $entity) {
            return $entity;
        }));
    }

    /**
     * 指定条件を元にクエリビルダにWHERE句をセットする
     *
     * @param  Builder  $queryBuilder
     * @param  array    $conditions
     * @return void
     * @throws Exception
     */
    protected function buildWhereClauseByConditions(Builder &$queryBuilder, array $conditions = [])
    {
        $table = $queryBuilder->getModel()->getTable();

        if (array_key_exists('id', $conditions)) {
            $id = null === $conditions['id'] || '' === $conditions['id'] ? null : (int) $conditions['id'];
            if (null !== $id) {
                $queryBuilder->where("{$table}.id", '=', $id);
            }
        }

        if (array_key_exists('ids', $conditions)) {
            $ids = null === $conditions['ids'] || '' === $conditions['ids']
                 ? null
                 : (is_json($conditions['ids']) ? (array) json_decode($conditions['ids']) : (array) $conditions['ids']);
            if (is_array($ids) && 0 < count($ids)) {
                $ids = array_map('intval', $ids);
                $queryBuilder->whereIn("{$table}.id", $ids);
            }
        }

        if (array_key_exists('ignore_ids', $conditions)) {
            $ignoreIds = null === $conditions['ignore_ids'] || '' === $conditions['ignore_ids']
                       ? null
                       : (is_json($conditions['ignore_ids']) ? (array) json_decode($conditions['ignore_ids']) : (array) $conditions['ignore_ids']);
            if (is_array($ignoreIds) && 0 < count($ignoreIds)) {
                $ignoreIds = array_map('intval', $ignoreIds);
                $queryBuilder->whereNotIn("{$table}.id", $ignoreIds);
            }
        }
    }

    /**
     * 指定条件を元にクエリビルダにORDER BY句をセットする
     *
     * @param  Builder  $queryBuilder
     * @param  array    $conditions
     * @return void
     */
    protected function buildOrderbyClauseByConditions(Builder &$queryBuilder, array $conditions = [])
    {
        $table = $queryBuilder->getModel()->getTable();

        if (array_key_exists('orders', $conditions) && is_array($conditions['orders'])) {
            foreach ($conditions['orders'] as $orderby => $order) {
                if ('random' == $orderby) {
                    $queryBuilder->inRandomOrder();
                } else {
                    $queryBuilder->orderBy($orderby, $order);
                }
            }
            $queryBuilder->orderBy("{$table}.id", 'DESC');
        } elseif (array_key_exists('orderby', $conditions)) {
            $orderby = null === $conditions['orderby'] || '' === $conditions['orderby'] ? null : (string) $conditions['orderby'];
            $order   = isset($conditions['order']) && null !== $conditions['order'] && '' !== $conditions['order'] ? (string) $conditions['order'] : 'ASC';
            $queryBuilder->orderBy($orderby, $order)
                         ->orderBy("{$table}.id", 'DESC');
        } elseif (array_key_exists('orderByRaws', $conditions)) {
            foreach ($conditions['orderByRaws'] as $orderby => $order) {
                if ('' == $order) {
                    $queryBuilder->orderByRaw($orderby);
                } else {
                    $queryBuilder->orderBy($orderby, $order);
                }
            }
            $queryBuilder->orderBy("{$table}.id", 'DESC');
        } else {
            foreach (static::$defaultOrders as $column => $order) {
                $queryBuilder->orderBy("{$table}.{$column}", $order);
            }
        }
    }
}
