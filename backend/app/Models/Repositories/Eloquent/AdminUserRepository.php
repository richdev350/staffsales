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
use App\Models\Entities\AdminUser;
use App\Models\Repositories\Contracts\AdminUserRepositoryInterface;
use App\Models\Repositories\Eloquent\Repository;

final class AdminUserRepository extends Repository implements AdminUserRepositoryInterface
{
    /**
     * モデル
     *
     */
    protected static $model = AdminUser::class;

    /**
     * 指定ログインIDのモデルを取得して返す
     *
     */
    public function findByLoginId(string $login_id)
    {
        $query = AdminUser::query();
        $query->where('login_id', '=', $login_id);

        return $query->first();
    }

    /**
     * 指定メールアドレスのモデルを取得して返す
     *
     */
    public function findByEmail(string $email)
    {
        $query = AdminUser::query();
        $query->where('email', '=', $email);

        return $query->first();
    }

    /**
     * 指定トークンのモデルを取得して返す
     *
     */
    public function findByToken(string $token)
    {
        $query = AdminUser::query();
        $query->where('token', '=', $token);

        return $query->first();
    }

    /**
     * トークンを生成して返す
     *
     */
    public function createToken(string $hashKey): string
    {
        return hash_hmac('sha256', Str::random(40), $hashKey);
    }

    /**
     * 再帰的に存在確認をしてユニークなトークンを生成して返す
     *
     */
    public function createUniqueToken(string $hashKey): string
    {
        $token = $this->createToken($hashKey);
        if ($this->existsToken($token)) {
            return $this->createUniqueToken($hashKey);
        } else {
            return $token;
        }
    }

    /**
     * トークンがすでに存在するかどうかを返す
     *
     */
    public function existsToken(string $token): bool
    {
        $query = AdminUser::query();
        $query->where('token', '=', $token);

        $admin_user = $query->first();

        return $admin_user instanceof AdminUser;
    }

    public function getListByRole(string $role): Collection
    {
        return AdminUser::role($role)->get();
    }

    /**
     * 指定条件を元にクエリビルダにWHERE句をセットする
     *
     */
    protected function buildWhereClauseByConditions(Builder &$queryBuilder, array $conditions = [])
    {
        parent::buildWhereClauseByConditions($queryBuilder, $conditions);

        $table = $queryBuilder->getModel()->getTable();

        if (array_key_exists('email', $conditions)) {
            $email = null === $conditions['email'] || '' === $conditions['email'] ? null : (string) $conditions['email'];
            if (null !== $email) {
                $queryBuilder->where("{$table}.email", '=', $email);
            }
        }

        if (array_key_exists('name', $conditions)) {
            $name = null === $conditions['name'] || '' === $conditions['name'] ? null : (string) $conditions['name'];
            if (null !== $name) {
                $queryBuilder->where("{$table}.name", 'LIKE', $name . "%");
            }
        }

        if (array_key_exists('token', $conditions)) {
            $token = null === $conditions['token'] || '' === $conditions['token'] ? null : (string) $conditions['token'];
            if (null !== $token) {
                $queryBuilder->where("{$table}.token", '=', $token);
            }
        }
    }
}
