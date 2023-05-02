<?php
declare(strict_types=1);

namespace App\Services\Maker;

use Throwable;
use Exception;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Entities\Maker;
use App\Models\Repositories\Contracts\MakerRepositoryInterface;

class DeleteMakerService
{
    private $repository;

    private $request;
    public function __construct(MakerRepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request    = $request;
    }

    public function delete($inputs = null): Maker
    {
        try {
            return DB::transaction(function () {
                $maker = $this->repository->find((int) $this->request->offsetGet('id'));
                $this->repository->delete($maker);
                return $maker;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}
