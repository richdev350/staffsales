<?php
declare(strict_types=1);

namespace app\Models\Repositories\Contracts;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Entities\ItemCategory;

interface ItemCategoryRepositoryInterface
{
    public function findById(int $id);

    public function findAllByParentId(int $parent_id);

    public function findItemsCountPerCategory(bool $disable_category_class_one, bool $disable_category_special);
}
