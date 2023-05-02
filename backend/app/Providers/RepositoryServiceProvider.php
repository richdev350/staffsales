<?php
declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// インターフェース
use App\Models\Repositories\Contracts\AdminUserRepositoryInterface;
use App\Models\Repositories\Contracts\RoleRepositoryInterface;
use App\Models\Repositories\Contracts\MakerRepositoryInterface;
use App\Models\Repositories\Contracts\PrefectureRepositoryInterface;
use App\Models\Repositories\Contracts\ShopRepositoryInterface;
use App\Models\Repositories\Contracts\DesiredTimeRepositoryInterface;
use App\Models\Repositories\Contracts\ItemCategoryRepositoryInterface;
use App\Models\Repositories\Contracts\ItemRepositoryInterface;
use App\Models\Repositories\Contracts\FileRepositoryInterface;
use App\Models\Repositories\Contracts\OrderRepositoryInterface;
use App\Models\Repositories\Contracts\OrderDetailRepositoryInterface;
use App\Models\Repositories\Contracts\RegionRepositoryInterface;
use App\Models\Repositories\Contracts\TagRepositoryInterface;
use App\Models\Repositories\Contracts\PublishRepositoryInterface;

// 実装クラス
use App\Models\Repositories\Eloquent\AdminUserRepository;
use App\Models\Repositories\Eloquent\RoleRepository;
use App\Models\Repositories\Eloquent\MakerRepository;
use App\Models\Repositories\Eloquent\PrefectureRepository;
use App\Models\Repositories\Eloquent\ShopRepository;
use App\Models\Repositories\Eloquent\DesiredTimeRepository;
use App\Models\Repositories\Eloquent\ItemCategoryRepository;
use App\Models\Repositories\Eloquent\ItemRepository;
use App\Models\Repositories\Eloquent\FileRepository;
use App\Models\Repositories\Eloquent\OrderRepository;
use App\Models\Repositories\Eloquent\OrderDetailRepository;
use App\Models\Repositories\Eloquent\RegionRepository;
use App\Models\Repositories\Eloquent\TagRepository;
use App\Models\Repositories\Eloquent\PublishRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * リポジトリのインターフェースと実装クラスのマッピングリストを返す
     *
     * @return array
     */
    private function repositories()
    {
        return [
            AdminUserRepositoryInterface::class  => AdminUserRepository::class,
            RoleRepositoryInterface::class  => RoleRepository::class,
            MakerRepositoryInterface::class => MakerRepository::class,
            PrefectureRepositoryInterface::class  => PrefectureRepository::class,
            ShopRepositoryInterface::class  => ShopRepository::class,
            DesiredTimeRepositoryInterface::class  => DesiredTimeRepository::class,
            ItemCategoryRepositoryInterface::class  => ItemCategoryRepository::class,
            ItemRepositoryInterface::class  => ItemRepository::class,
            FileRepositoryInterface::class  => FileRepository::class,
            OrderRepositoryInterface::class => OrderRepository::class,
            OrderDetailRepositoryInterface::class => OrderDetailRepository::class,
            RegionRepositoryInterface::class => RegionRepository::class,
            TagRepositoryInterface::class => TagRepository::class,
            PublishRepositoryInterface::class => PublishRepository::class,
        ];
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->repositories() as $interface => $concrete) {
            $this->app->bind($interface, $concrete);
        }
    }
}
