<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use App\Services\Admin\AuthenticateAdminUserService;
use App\Services\ItemCategory\ListItemCategoriesService;

class DataSharingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(ListItemCategoriesService $listItemCategoriesService)
    {
        $is_admin_login = AuthenticateAdminUserService::isAuthenticated();

        $root_categories = $listItemCategoriesService->rootList();
        $cache_key = "items_count_of_category";
        if (Cache::has($cache_key)) {
            $categories = Cache::get($cache_key);
        } else {
            $categories = $listItemCategoriesService->getAllArray($root_categories, false, false);
            Cache::put($cache_key, $categories, config('cache.lifetime'));
        }
        $categories = json_decode(json_encode($categories));

        view()->share(compact(
            'is_admin_login',
            'categories',
        ));
    }
}
