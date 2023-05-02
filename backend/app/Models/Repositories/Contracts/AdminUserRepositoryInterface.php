<?php
declare(strict_types=1);

namespace App\Models\Repositories\Contracts;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Entities\AdminUser;

interface AdminUserRepositoryInterface
{
    /**
     * 指定ログインIDのモデルを取得して返す
     *
     */
    public function findByloginId(string $login_id);

    /**
     * 指定メールアドレスのモデルを取得して返す
     *
     */
    public function findByEmail(string $email);

    /**
     * 指定トークンのモデルを取得して返す
     *
     */
    public function findByToken(string $token);

    /**
     * トークンを生成して返す
     *
     */
    public function createToken(string $hashKey): string;

    /**
     * 再帰的に存在確認をしてユニークなトークンを生成して返す
     *
     */
    public function createUniqueToken(string $hashKey): string;

    /**
     * トークンがすでに存在するかどうかを返す
     *
     */
    public function existsToken(string $token): bool;
    public function getListByRole(string $role): Collection;
}
