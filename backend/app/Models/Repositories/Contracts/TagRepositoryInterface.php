<?php
declare(strict_types=1);

namespace app\Models\Repositories\Contracts;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Entities\Tag;

interface TagRepositoryInterface
{
    public function findByName(string $name);
}
