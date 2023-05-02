<?php
declare(strict_types=1);

namespace app\Models\Repositories\Eloquent;

use Throwable;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Entities\Publish;
use App\Models\Repositories\Contracts\PublishRepositoryInterface;
use App\Models\Repositories\Eloquent\Repository;

final class PublishRepository extends Repository implements PublishRepositoryInterface
{
    protected static $model = Publish::class;
}
