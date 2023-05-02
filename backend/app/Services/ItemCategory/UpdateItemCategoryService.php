<?php
declare(strict_types=1);

namespace App\Services\ItemCategory;

use Throwable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Entities\ItemCategory;
use App\Models\Repositories\Contracts\ItemCategoryRepositoryInterface;
use App\Http\Requests\ItemCategory\SaveItemCategoryRequestFilter;
use App\Http\Requests\ItemCategory\SaveItemCategoryRequest;
use App\Services\Traits\Filterable;
use App\Services\Traits\Validatable;

class UpdateItemCategoryService
{
    use Filterable,
        Validatable;

    private $repository;

    private $request;

    public function __construct(ItemCategoryRepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request    = $request;

        $this->setRequestFilter(new SaveItemCategoryRequestFilter());
        $this->setFormRequest(new SaveItemCategoryRequest());
        $this->init();
    }

    public function init()
    {
        if (! $this->request->isMethod('GET')) {
            $this->filterInputs();
            return;
        }

        $this->request->flush();

        $item_category = $this->repository->find((int) $this->request->offsetGet('id'));

        $defaults = [
            'name' => $item_category->name,
        ];

        $this->request->merge($defaults);
    }

    public function update($inputs = null): ItemCategory
    {
        if (is_null($inputs)) {
            $inputs = $this->request->except('action');
        }

        try {
            return DB::transaction(function () use ($inputs) {
                $item_category = $this->repository->find((int) $this->request->offsetGet('id'));
                $item_category = $this->repository->edit($item_category, $inputs);

                $item_category = $this->repository->persist($item_category);

                return $item_category;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}
