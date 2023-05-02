<?php
declare(strict_types=1);

namespace App\Models\Repositories\Eloquent;

use Throwable;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Entities\Role;
use App\Models\Repositories\Contracts\RoleRepositoryInterface;
use App\Models\Repositories\Eloquent\Repository;

final class RoleRepository extends Repository implements RoleRepositoryInterface
{
    /**
     * モデル
     *
     * @var User
     */
    protected static $model = Role::class;

}
