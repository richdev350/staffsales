<?php
declare(strict_types=1);

namespace app\Models\Repositories\Eloquent;

use Throwable;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Entities\Prefecture;
use App\Models\Repositories\Contracts\PrefectureRepositoryInterface;
use App\Models\Repositories\Eloquent\Repository;

final class PrefectureRepository extends Repository implements PrefectureRepositoryInterface
{
    protected static $model = Prefecture::class;
}
