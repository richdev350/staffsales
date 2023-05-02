<?php
declare(strict_types=1);

namespace App\Services\DesiredTime;

use Throwable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Entities\DesiredTime;
use App\Models\Repositories\Contracts\DesiredTimeRepositoryInterface;
use App\Services\Admin\AuthenticateDesiredTimeService;

class DeleteDesiredTimeService
{
    private $repository;
    private $request;

    public function __construct(DesiredTimeRepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request    = $request;
    }

    public function delete($inputs = null): DesiredTime
    {
        try {
            return DB::transaction(function () {
                $desired_time = $this->repository->find((int) $this->request->offsetGet('id'));
                $this->repository->delete($desired_time);
                return $desired_time;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}
