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
use App\Http\Requests\Maker\SaveMakerRequestFilter;
use App\Http\Requests\Maker\SaveMakerRequest;
use App\Services\Traits\Filterable;
use App\Services\Traits\Validatable;

class UpdateMakerService
{
    use Filterable,
        Validatable;

    private $repository;
    private $request;

    public function __construct(MakerRepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request    = $request;

        $this->setRequestFilter(new SaveMakerRequestFilter());
        $this->setFormRequest(new SaveMakerRequest());
        $this->init();
    }

    public function init()
    {
        if (! $this->request->isMethod('GET')) {
            $this->filterInputs();
            return;
        }

        $this->request->flush();

        $maker = $this->repository->find((int) $this->request->offsetGet('id'));

        $defaults = [
            'name' => $maker->name,
        ];

        $this->request->merge($defaults);
    }

    public function update($inputs = null): Maker
    {
        if (is_null($inputs)) {
            $inputs = $this->request->except('action');
        }

        try {
            return DB::transaction(function () use ($inputs) {
                $maker = $this->repository->find((int) $this->request->offsetGet('id'));
                $maker = $this->repository->edit($maker, $inputs);
                $maker = $this->repository->persist($maker);
                return $maker;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}
