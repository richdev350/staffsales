<?php
declare(strict_types=1);

namespace app\Models\Repositories\Eloquent;

use Throwable;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Entities\Item;
use App\Models\Repositories\Contracts\ItemRepositoryInterface;
use App\Models\Repositories\Eloquent\Repository;

final class ItemRepository extends Repository implements ItemRepositoryInterface
{
    const ITEM_CATEGORY_ID_MEDICAL = 1;
    const ITEM_CATEGORY_ID_SPECIAL = 827;
    const RECENT_ITEMS_LIMIT = 1000;

    protected static $model = Item::class;

    public function findByJan(string $jan)
    {
        $query = Item::query();
        $query->where('jan', '=', $jan)->orderBy('sort_no', 'ASC');

        return $query->first();
    }

    protected function buildWhereClauseByConditions(Builder &$queryBuilder, array $conditions = [])
    {
        $queryBuilder->leftJoin(DB::raw("(select item_id, count(*) sale_count, sum(amount) sale_amount from order_details group by item_id) order_details"), function($join) {
            $join->on("items.id", "=", "order_details.item_id");
        });

        parent::buildWhereClauseByConditions($queryBuilder, $conditions);
        $table = $queryBuilder->getModel()->getTable();

        if (array_key_exists('item_category_id', $conditions)) {
            $item_category_id = null === $conditions['item_category_id'] || '' === $conditions['item_category_id'] ? null : $conditions['item_category_id'];
            if (null !== $item_category_id) {
                $queryBuilder->whereIn('items.id', function($queryBuilder) use($item_category_id) {
                    $queryBuilder->select('item_id')->from('item_categories_items')->where('item_category_id', $item_category_id);
                });
            }
        }

        if (array_key_exists('sort_no_up', $conditions)) {
            $sort_no_up = null === $conditions['sort_no_up'] || '' === $conditions['sort_no_up'] ? null : $conditions['sort_no_up'];
            if (null !== $sort_no_up) {
                $queryBuilder->where("{$table}.sort_no", '<', $sort_no_up);
            }
        }

        if (array_key_exists('sort_no_down', $conditions)) {
            $sort_no_down = null === $conditions['sort_no_down'] || '' === $conditions['sort_no_down'] ? null : $conditions['sort_no_down'];
            if (null !== $sort_no_down) {
                $queryBuilder->where("{$table}.sort_no", '>', $sort_no_down);
            }
        }

        if (array_key_exists('jan', $conditions)) {
            $jan = null === $conditions['jan'] || '' === $conditions['jan'] ? null : $conditions['jan'];
            if (null !== $jan) {
                $queryBuilder->where("{$table}.jan", 'LIKE', $jan.'%');
            }
        }

        if (array_key_exists('maker_id', $conditions)) {
            $maker_id = null === $conditions['maker_id'] || '' === $conditions['maker_id'] ? null : $conditions['maker_id'];
            if (null !== $maker_id) {
                $queryBuilder->where("{$table}.maker_id", '=', $maker_id);
            }
        }

        if (array_key_exists('is_visible', $conditions)) {
            $is_visible = null === $conditions['is_visible'] || '' === $conditions['is_visible'] ? null : $conditions['is_visible'];
            if (null !== $is_visible) {
                $queryBuilder->where("{$table}.is_visible", '=', $is_visible);
            }
        }

        if (array_key_exists('is_visibles', $conditions)) {
            $is_visibles = null === $conditions['is_visibles'] || '' === $conditions['is_visibles'] ? null : $conditions['is_visibles'];
            if (null !== $is_visibles && !empty($is_visibles)) {
                $queryBuilder->whereIn("{$table}.is_visible", $is_visibles);
            }
        }

        if (array_key_exists('maker_ids', $conditions)) {
            $maker_ids = null === $conditions['maker_ids'] || '' === $conditions['maker_ids'] ? null : $conditions['maker_ids'];
            if (null !== $maker_ids && !empty($maker_ids)) {
                $queryBuilder->whereIn('items.maker_id', $maker_ids);
            }
        }

        if (array_key_exists('text', $conditions)) {
            $text = null === $conditions['text'] || '' === $conditions['text'] ? null : (string) $conditions['text'];
            if (null !== $text) {
                $queryBuilder->where(function($queryBuilder) use($text){
                    $text = escape_filter($text);
                    $queryBuilder->orWhere('items.name', 'LIKE', "%$text%");
                    $queryBuilder->orWhere('items.jan', 'LIKE', "$text%");
                });
            }
        }

        if (array_key_exists('search', $conditions)) {
            $search = null === $conditions['search'] || '' === $conditions['search'] ? null : (string) $conditions['search'];
            if (null !== $search) {
                $search_array = get_keyword_array($search);
                foreach ($search_array as $search_item) {
                    $queryBuilder->where(function($queryBuilder) use($search_item){
                        $search_item = escape_filter($search_item);
                        $queryBuilder->orWhere("items.name", "LIKE", "%$search_item%");
                        $queryBuilder->orWhere("items.jan", "LIKE", "$search_item%");
                        $queryBuilder->orWhereIn('items.id', function($queryBuilder) use($search_item) {
                            $queryBuilder->select('item_id')->from('items_tags')->whereIn('tag_id', function($queryBuilder) use($search_item) {
                                $queryBuilder->select('id')->from('tags')->where('name', 'like', "%$search_item%");
                            });
                        });
                    });
                }
            }
        }

        if (array_key_exists('self_medication', $conditions)) {
            $self_medication = null === $conditions['self_medication'] || '' === $conditions['self_medication'] ? null : $conditions['self_medication'];
            if (null !== $self_medication) {
                $queryBuilder->where("items.self_medication", "=", $self_medication);
            }
        }

        if (array_key_exists('price_id', $conditions)) {
            $price_id = null === $conditions['price_id'] || '' === $conditions['price_id'] ? null : $conditions['price_id'];
            if (null !== $price_id) {
                $price_search_list = price_search_list();
                $price_info = $price_search_list[$price_id];
                if ($price_info['min']) {
                    $queryBuilder->where("items.price", '>=', $price_info['min']);
                }

                if ($price_info['max']) {
                    $queryBuilder->where("items.price", '<', $price_info['max']);
                }
            }
        }
    }

}
