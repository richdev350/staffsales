<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Http\Controllers\Controller;
use App\Models\Entities\ItemCategory;
use App\Services\ItemCategory\ListItemCategoriesService;
use App\Services\ItemCategory\CreateItemCategoryService;
use App\Services\ItemCategory\UpdateItemCategoryService;
use App\Services\ItemCategory\DeleteItemCategoryService;

class ItemCategoryController extends Controller
{
    public function list(
        ListItemCategoriesService $listItemCategoriesService,
        $condition = null
    ) {
        $messages = [];
        $errors = new MessageBag;
        $list = $listItemCategoriesService->getOrCreateRootList();
        $tree_json = $listItemCategoriesService->getJsTreeJson($list);
        response()->json([
            'messages' => $messages,
            'errors' => $errors,
            'tree_json' => $tree_json
        ]);
    }

    public function store(
        ListItemCategoriesService $listItemCategoriesService,
        CreateItemCategoryService $createItemCategoryService,
        Request $request
    ){
        $messages = [];
        $errors = new MessageBag;

        if ($createItemCategoryService->passesValidation()) {
            try {
                $ItemCategory = $createItemCategoryService->create();
                $messages[] = 'カテゴリを保存しました。';
            } catch (Throwable $exception) {
                $errors->add('fatal', 'エラーが発生しました。');
            }
        } else {
            $errors = $createItemCategoryService->getValidationMessages();
        }

        $list = $listItemCategoriesService->getOrCreateRootList();
        $tree_json = $listItemCategoriesService->getJsTreeJson($list);

        return response()->json([
            'messages' => $messages,
            'errors' => $errors,
            'tree_json' => $tree_json
        ]);
    }

    public function update(
        ListItemCategoriesService $listItemCategoriesService,
        UpdateItemCategoryService $updateItemCategoryService,
        Request $request
    ) {
        $messages = [];
        $errors = new MessageBag;

        if ($updateItemCategoryService->passesValidation()) {
            try {
                $item_category = $updateItemCategoryService->update();
                $messages[] = 'カテゴリを保存しました。';
            } catch (Throwable $exception) {
                $errors->add('fatal', 'エラーが発生しました。');
            }

        } else {
            $errors = $updateItemCategoryService->getValidationMessages();
        }

        $list = $listItemCategoriesService->getOrCreateRootList();
        $tree_json = $listItemCategoriesService->getJsTreeJson($list);

        return response()->json([
            'messages' => $messages,
            'errors' => $errors,
            'tree_json' => $tree_json
        ]);
    }

    public function destroy(
        ListItemCategoriesService $listItemCategoriesService,
        DeleteItemCategoryService $deleteItemCategoryService,
        Request $request
    ) {
        $messages = [];
        $errors = new MessageBag;

        try {
            $item_category = $deleteItemCategoryService->delete();
            $messages[] = sprintf('「%s」を削除しました。', $item_category->name);

        } catch (Throwable $exception) {
            throw $exception;
        }

        $list = $listItemCategoriesService->getOrCreateRootList();
        $tree_json = $listItemCategoriesService->getJsTreeJson($list);

        return response()->json([
            'messages' => $messages,
            'errors' => $errors,
            'tree_json' => $tree_json
        ]);
    }
}
