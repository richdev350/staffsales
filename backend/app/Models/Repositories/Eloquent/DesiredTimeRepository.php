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
use App\Models\Entities\DesiredTime;
use App\Models\Repositories\Contracts\DesiredTimeRepositoryInterface;
use App\Models\Repositories\Eloquent\Repository;

final class DesiredTimeRepository extends Repository implements DesiredTimeRepositoryInterface
{
    protected static $model = DesiredTime::class;
}