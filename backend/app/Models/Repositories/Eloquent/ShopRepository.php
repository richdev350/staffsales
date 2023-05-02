<?php
declare(strict_types=1);

namespace App\Models\Repositories\Eloquent;

use Throwable;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Entities\Shop;
use App\Models\Repositories\Contracts\ShopRepositoryInterface;
use App\Models\Repositories\Eloquent\Repository;

final class ShopRepository extends Repository implements ShopRepositoryInterface
{
    protected static $model = Shop::class;

    protected function buildWhereClauseByConditions(Builder &$queryBuilder, array $conditions = [])
    {
        parent::buildWhereClauseByConditions($queryBuilder, $conditions);

        $table = $queryBuilder->getModel()->getTable();

        if (array_key_exists('code', $conditions)) {
            $code = null === $conditions['code'] || '' === $conditions['code'] ? null : (string) $conditions['code'];
            if (null !== $code) {
                $queryBuilder->where("{$table}.code", '=', $code);
            }
        }

        if (array_key_exists('name', $conditions)) {
            $name = null === $conditions['name'] || '' === $conditions['name'] ? null : (string) $conditions['name'];
            if (null !== $name) {
                $queryBuilder->where("{$table}.name", 'LIKE', $name . "%");
            }
        }

        if (array_key_exists('zip_code', $conditions)) {
            $zip_code = null === $conditions['zip_code'] || '' === $conditions['zip_code'] ? null : (string) $conditions['zip_code'];
            if (null !== $zip_code) {
                $queryBuilder->where("{$table}.zip_code", '=', $zip_code);
            }
        }

        if (array_key_exists('region_id', $conditions)) {
            $region_id = null === $conditions['region_id'] || '' === $conditions['region_id'] ? null : $conditions['region_id'];
            if (null !== $region_id) {
                $queryBuilder->whereIn('shops.prefecture_id', function($queryBuilder) use($region_id) {
                    $queryBuilder->select('id')->from('prefectures')->where('region_id', $region_id);
                });
            }
        }

        if (array_key_exists('prefecture_id', $conditions)) {
            $prefecture_id = null === $conditions['prefecture_id'] || '' === $conditions['prefecture_id'] ? null : (string) $conditions['prefecture_id'];
            if (null !== $prefecture_id) {
                $queryBuilder->where("{$table}.prefecture_id", '=', $prefecture_id);
            }
        }

        if (array_key_exists('city', $conditions)) {
            $city = null === $conditions['city'] || '' === $conditions['city'] ? null : (string) $conditions['city'];
            if (null !== $city) {
                $queryBuilder->where("{$table}.city", 'LIKE', $city . "%");
            }
        }

        if (array_key_exists('address', $conditions)) {
            $address = null === $conditions['address'] || '' === $conditions['address'] ? null : (string) $conditions['address'];
            if (null !== $city) {
                $queryBuilder->where("{$table}.address", 'LIKE', $city . "%");
            }
        }

        if (array_key_exists('tel', $conditions)) {
            $tel = null === $conditions['tel'] || '' === $conditions['tel'] ? null : (string) $conditions['tel'];
            if (null !== $tel) {
                $queryBuilder->where("{$table}.tel", '=', $tel);
            }
        }

    }
}
