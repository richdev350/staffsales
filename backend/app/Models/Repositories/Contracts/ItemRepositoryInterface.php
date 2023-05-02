<?php
declare(strict_types=1);

namespace app\Models\Repositories\Contracts;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Entities\Item;

interface ItemRepositoryInterface
{
    public function findByJan(string $jan);
}
