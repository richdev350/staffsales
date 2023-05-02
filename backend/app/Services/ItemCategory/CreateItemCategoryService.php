<?php
declare(strict_types=1);

namespace App\Services\ItemCategory;

use Throwable;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Entities\ItemCategory;
use App\Models\Repositories\Contracts\ItemCategoryRepositoryInterface;
use App\Http\Requests\ItemCategory\CreateItemCategoryRequestFilter;
use App\Http\Requests\ItemCategory\CreateItemCategoryRequest;
use App\Services\Traits\Filterable;
use App\Services\Traits\Validatable;

class CreateItemCategoryService
{
    use Filterable,
        Validatable;

    const ROOT_PARENT_ID = 0;

    private $repository;
    private $request;

    public function __construct(ItemCategoryRepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request    = $request;

        $this->setRequestFilter(new CreateItemCategoryRequestFilter());
        $this->setFormRequest(new CreateItemCategoryRequest());
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

    public function create($inputs = null): ItemCategory
    {
        if (is_null($inputs)) {
            $inputs = $this->request->except('action');
        }

        try {
            return DB::transaction(function () use ($inputs) {
                if(self::ROOT_PARENT_ID === $inputs['parent_id']){
                    $item_category = $this->repository->new($inputs);
                }else{
                    $item_category = $this->repository->new($inputs);
                    $parent_item_category = $this->repository->findById($inputs['parent_id']);
                    $parent_item_category->addChild($item_category);
                }

                $item_category = $this->repository->persist($item_category);

                return $item_category;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}
