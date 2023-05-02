<?php

namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ItemCategory\ListItemCategoriesService;
use App\Services\Maker\ListMakersService;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Cache;

class BaseGeneralController extends Controller
{
    public function __construct(
        ListMakersService $listMakersService,
        ListItemCategoriesService $listItemCategoriesService,
        Request $request
    )
    {
        if (env('GENERAL_ON_MAINTENANCE')){
            if (!AllowIpsOnMaintenance()) {
                throw new HttpException(503);
            }
            // if(!in_array($request->getClientIp(), explode(',', env('ALLOW_IPS_ON_MAINTENANCE')))) {
            //     throw new HttpException(503);
            // }
        }

        $conditions = [];
        $makers = $listMakersService->list();
        $root_categories = $listItemCategoriesService->rootList();

        $cache_key = "items_count_of_category";
        if(Cache::has($cache_key)){
            $categories = Cache::get($cache_key);
        } else {
            $categories = $listItemCategoriesService->getAllArray($root_categories, false, false);
            Cache::put($cache_key, $categories, config('cache.lifetime'));
        }

        $categories = json_decode(json_encode($categories));

        view()->share(compact(
            'conditions',
            'makers',
            'categories',
        ));
    }
}
