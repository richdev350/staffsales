<?php
declare(strict_types=1);

namespace App\Services\Shop;

use Throwable;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Entities\Shop;
use App\Models\Repositories\Contracts\ShopRepositoryInterface;
use App\Http\Requests\Shop\SaveShopRequestFilter;
use App\Http\Requests\Shop\SaveShopRequest;
use App\Services\Traits\Filterable;
use App\Services\Traits\Validatable;

class CreateShopService
{
    use Filterable,
        Validatable;

    private $repository;
    private $request;

    public function __construct(ShopRepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request    = $request;

        $this->setRequestFilter(new SaveShopRequestFilter());
        $this->setFormRequest(new SaveShopRequest());
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

    public function create($inputs = null): Shop
    {
        if (is_null($inputs)) {
            $inputs = $this->request->except('action');
        }

        try {
            return DB::transaction(function () use ($inputs) {
                $shop = $this->repository->new($inputs);
                $shop = $this->repository->persist($shop);
                $admin_user_ids = [$inputs['manager_id'],$inputs['staff_id']];

                if (isset($admin_user_ids) && is_array($admin_user_ids)) {
                    $shop->admin_users()->sync($admin_user_ids);
                }else{
                    $shop->admin_users()->sync([]);
                }

                return $shop;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}
