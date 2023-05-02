<?php
declare(strict_types=1);

namespace app\Models\Repositories\Eloquent;

use Throwable;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Entities\OrderDetail;
use App\Models\Repositories\Contracts\OrderDetailRepositoryInterface;
use App\Models\Repositories\Eloquent\Repository;

final class OrderDetailRepository extends Repository implements OrderDetailRepositoryInterface
{
    protected static $model = OrderDetail::class;
}
