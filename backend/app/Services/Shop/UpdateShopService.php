<?php
declare(strict_types=1);

namespace App\Services\Shop;

use Throwable;
use Exception;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Entities\Shop;
use App\Models\Repositories\Contracts\ShopRepositoryInterface;
use App\Http\Requests\Shop\SaveShopRequestFilter;
use App\Http\Requests\Shop\SaveShopRequest;
use App\Services\Traits\Filterable;
use App\Services\Traits\Validatable;

class UpdateShopService
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

        $shop = $this->repository->find((int) $this->request->offsetGet('id'));

        $defaults = [
            'code'          => $shop->code,
            'name'          => $shop->name,
            'zip_code'      => $shop->zip_code,
            'region_name'   => $shop->prefecture?$shop->prefecture->region->name:'',
            'prefecture_id' => $shop->prefecture_id,
            'city'          => $shop->city,
            'address'       => $shop->address,
            'tel'           => $shop->tel,
            'manager_id'    => $shop->manager_id,
            'staff_id'      => $shop->staff_id,
        ];

        $this->request->merge($defaults);
    }

    public function update($inputs = null): Shop
    {
        if (is_null($inputs)) {
            $inputs = $this->request->except('action');
        }

        try {
            return DB::transaction(function () use ($inputs) {
                $shop = $this->repository->find((int) $this->request->offsetGet('id'));
                $shop = $this->repository->edit($shop, $inputs);
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

    public function ignoreItems($inputs = null): int
    {
        if (is_null($inputs)) {
            $inputs = $this->request->except('action');
        }

        try {
            return DB::transaction(function () use ($inputs) {
                $shop = $this->repository->find((int) $this->request->offsetGet('shop_id'));
                $ignore_item_ids = [];
                if($inputs['targets']){
                    $ignore_item_ids = array_map('intval', explode(',', $inputs['targets']));
                }

                if (isset($ignore_item_ids) && is_array($ignore_item_ids)) {
                    $shop->ignore_items()->sync($ignore_item_ids);
                }else{
                    $shop->ignore_items()->sync([]);
                }

                return count($ignore_item_ids);
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}
