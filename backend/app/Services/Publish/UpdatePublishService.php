<?php
declare(strict_types=1);

namespace App\Services\Publish;

use Throwable;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Application;
use App\Exceptions\ImageExifException;
use App\Models\Entities\Publish;
use App\Models\Repositories\Contracts\PublishRepositoryInterface;
use App\Http\Requests\Publish\SavePublishRequest;
use App\Http\Requests\Publish\SavePublishRequestFilter;
use App\Services\Admin\AuthenticateAdminUserService;
use App\Services\Traits\Filterable;
use App\Services\Traits\Validatable;
use Illuminate\Support\Collection;

class UpdatePublishService
{
    use Filterable,
        Validatable;

    private $repository;
    private $publishRepository;

    private $request;
    public $timestamps  = false ;

    public function __construct(
        PublishRepositoryInterface $repository,
        Request $request
    ) {
        $this->repository       = $repository;
        $this->request          = $request;

        $this->setRequestFilter(new SavePublishRequestFilter());
        $this->setFormRequest(new SavePublishRequest());
        $this->init();
    }

    public function init()
    {

        {
            if (! $this->request->isMethod('GET')) {
                $this->filterInputs();
                return;
            }

            $this->request->flush();

            $defaults = [];
            $this->request->merge($defaults);
        }

    }

    public function update($inputs = null): Publish
    {
        if (is_null($inputs)) {
            $inputs = $this->request->except('action');
        }
        try {
            return DB::transaction(function () use ($inputs) {
                if ($this->request->offsetExists('id')) {
                    $publish = $this->repository->find((int) $this->request->offsetGet('id'));
                } else if (isset($inputs['id'])) {
                    $publish = $this->repository->find((int) $inputs['id']);
                }
                if(!isset($inputs['spec'])){
                    $inputs['spec'] = [];
                }
                $publish = $this->repository->edit($publish, $inputs);

                $publish = $this->repository->persist($publish);
                return $publish;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}
