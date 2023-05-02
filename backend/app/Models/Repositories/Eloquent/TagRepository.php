<?php
declare(strict_types=1);

namespace app\Models\Repositories\Eloquent;

use Throwable;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Entities\Tag;
use App\Models\Repositories\Contracts\TagRepositoryInterface;
use App\Models\Repositories\Eloquent\Repository;

final class TagRepository extends Repository implements TagRepositoryInterface
{
    protected static $model = Tag::class;

    public function findByName(string $name){
        $query = Tag::query();
        $query->where("name", '=', $name);

        return $query->first();
    }
}
