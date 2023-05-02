<?php
declare(strict_types=1);

namespace app\Models\Repositories\Eloquent;

use Throwable;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Entities\ItemCategory;
use App\Models\Repositories\Contracts\ItemCategoryRepositoryInterface;
use App\Models\Repositories\Eloquent\Repository;
use App\Models\Repositories\Eloquent\ItemRepository;

final class ItemCategoryRepository extends Repository implements ItemCategoryRepositoryInterface
{
    protected static $model = ItemCategory::class;

    public function findById(int $id)
    {
        $query = ItemCategory::query();
        $query->where('id', '=', $id)->orderBy('position', 'ASC');

        return $query->first();
    }

    public function findAllByParentId(?int $parent_id)
    {
        $query = ItemCategory::query();
        $query->where('parent_id', '=', $parent_id)->orderBy('position', 'ASC');

        return $query->get();
    }

    public function findItemsCountPerCategory(bool $disable_category_class_one, bool $disable_category_special) {

        $query = DB::table('item_categories_items')
            ->select('item_category_id', DB::raw('count(*) as total_count'))
            ->leftJoin('items', 'item_categories_items.item_id', '=', 'items.id')
            ->whereNotNull('items.id')
            ->whereNull('items.deleted_at')
            ->where('items.is_visible', true);

        if ($disable_category_class_one) {
            $query->whereNotIn('items.id', function($query) {
                $query->select('item_id')->from('item_categories_items')->whereIn('item_category_id', function($query) {
                    $query->select('id')->from('item_categories')->where('class_one', 1);
                });
            });
        }

        if ($disable_category_special) {
            $query->whereNotIn('items.id', function($query) {
                $query->select('item_id')->from('item_categories_items')->where('item_category_id', ItemRepository::ITEM_CATEGORY_ID_SPECIAL);
            });
        }

        $category_sum = $query->groupBy('item_category_id')->get();
        return $category_sum;
    }
}
