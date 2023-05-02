<?php
declare(strict_types=1);

namespace App\Services\DesiredTime;

use Throwable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Entities\DesiredTime;
use App\Models\Repositories\Contracts\DesiredTimeRepositoryInterface;
use App\Http\Requests\DesiredTime\SaveDesiredTimeRequestFilter;
use App\Http\Requests\DesiredTime\SaveDesiredTimeRequest;
use App\Services\Traits\Filterable;
use App\Services\Traits\Validatable;

class UpdateDesiredTimeService
{
    use Filterable,
        Validatable;

    private $repository;

    private $request;

    public function __construct(DesiredTimeRepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request    = $request;

        $this->setRequestFilter(new SaveDesiredTimeRequestFilter());
        $this->setFormRequest(new SaveDesiredTimeRequest());
        $this->init();
    }

    public function init()
    {
        if (! $this->request->isMethod('GET')) {
            $this->filterInputs();
            return;
        }

        $this->request->flush();

        $desired_time = $this->repository->find((int) $this->request->offsetGet('id'));

        $defaults = [
            'from' => $desired_time->from,
            'to'=> $desired_time->to,
            'period'=> $desired_time->period,
        ];

        $this->request->merge($defaults);
    }

    public function update($inputs = null): DesiredTime
    {
        if (is_null($inputs)) {
            $inputs = $this->request->except('action');
        }

        try {
            return DB::transaction(function () use ($inputs) {
                $desired_time = $this->repository->find((int) $this->request->offsetGet('id'));
                $desired_time = $this->repository->edit($desired_time, $inputs);

                $desired_time = $this->repository->persist($desired_time);

                return $desired_time;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}
