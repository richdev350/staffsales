<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Http\Controllers\Controller;
use App\Models\Entities\ItemCategory;
use App\Services\ItemCategory\ListItemCategoriesService;
use App\Services\ItemCategory\UpdateItemCategoryService;

class ItemCategoryController extends Controller
{
    public function list(
        ListItemCategoriesService $listItemCategoriesService,
        $condition = null
    ) {
        $list = $listItemCategoriesService->getOrCreateRootList();
        $tree_json = $listItemCategoriesService->getJsTreeJson($list);
        return response()->view('admins.item-categories.list', compact(
            'tree_json',
        ));
    }

}
