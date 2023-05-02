<?php
declare(strict_types=1);

namespace App\Services\Publish;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Entities\Publish;
use App\Models\Repositories\Contracts\PublishRepositoryInterface;
use App\Services\Traits\Conditionable;
use App\Services\Traits\Paginationable;

class ListPublishService
{
    use Conditionable,
        Paginationable;

    private $repository;
    private $request;

    public function __construct(PublishRepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request    = $request;
    }

    public function list($conditions = null, $limit = null, $offset = null): Collection
    {
        return $this->repository->list($conditions, $limit, $offset);
    }

}
