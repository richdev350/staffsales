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
use App\Models\Entities\File;
use App\Models\Repositories\Contracts\FileRepositoryInterface;
use App\Models\Repositories\Eloquent\Repository;

final class FileRepository extends Repository implements FileRepositoryInterface
{
    protected static $model = File::class;
}
