<?php
declare(strict_types=1);

namespace App\Services\Tag;

use Throwable;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Entities\Tag;
use App\Models\Repositories\Contracts\TagRepositoryInterface;
use App\Http\Requests\Tag\SaveTagRequestFilter;
use App\Http\Requests\Tag\SaveTagRequest;
use App\Services\Traits\Filterable;
use App\Services\Traits\Validatable;

class CreateTagService
{
    use Filterable,
        Validatable;

    private $repository;
    private $request;

    public function __construct(TagRepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request    = $request;

        $this->setRequestFilter(new SaveTagRequestFilter());
        $this->setFormRequest(new SaveTagRequest());
        $this->init();
    }

    public function init()
    {
        if (! $this->request->isMethod('GET')) {
            $this->filterInputs();
            return;
        }

        $this->request->flush();
    }

    public function create($inputs = null): Tag
    {
        if (is_null($inputs)) {
            $inputs = $this->request->except('action');
        }

        try {
            return DB::transaction(function () use ($inputs) {
                $tag = $this->repository->new($inputs);
                $this->repository->persist($tag);

                return $tag;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }

    public function findByName(string $name){
        return $this->repository->findByName($name);
    }
}
