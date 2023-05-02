<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Http\Controllers\Controller;
use Illuminate\Support\HtmlString;
use App\Services\Maker\ListMakersService;
use App\Services\ItemCategory\ListItemCategoriesService;
use App\Services\Item\ListItemsService;
use App\Services\Item\BatchItemsService;
use App\Services\Item\CreateItemService;
use App\Services\Item\UpdateItemService;
use App\Services\Item\DeleteItemService;
use App\Services\Region\ListRegionsService;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use App\Exports\ItemsExport;
use App\Imports\ItemsImport;
use App\Exceptions\ImportValidationException;
use App\Enums\Item\Label;
use App\Services\Item\SyncItemsService;

class ItemController extends Controller
{
    public function list(
        ListItemsService $listItemsService,
        ListMakersService $listMakersService,
        ListItemCategoriesService $listItemCategoriesService,
        $condition = null
    ) {
        $conditions = $listItemsService->conditionQueryToArray($condition);
        $makers = $listMakersService->list();
        $conditions['orders'] = ['sort_no' => 'ASC'];
        $paginator = $listItemsService->pagination($conditions);
        $item_categories = $listItemCategoriesService->list();

        return response()->view('admins.items.list', compact(
            'makers',
            'conditions',
            'paginator',
            'item_categories'
        ));
    }

    public function sort(
        ListItemsService $listItemsService,
        UpdateItemService $updateItemService,
        Request $request
    )
    {
        $ids = $action = $request->input('ids');
        $conditions = $request->input('conditions');
        $page = $request->input('page');
        $result = $updateItemService->setSortNum($ids);
        if($result){
            $request->session()->flash('message', 'ソート順を変更しました。');
        }
        $params = [];
        if($page){
            $params['page'] = $page;
        }
        if(isset($conditions) && count($conditions)){
            $params['condition'] = $listItemsService->conditionsToQuery($conditions, true);
        }
        return redirect()->route('admin.item.list', $params);
    }

    public function sortExchange(
        ListItemsService $listItemsService,
        UpdateItemService $updateItemService,
        Request $request
    )
    {
        $page = $request->input('page');
        $conditions = $request->input('conditions');
        $result = $updateItemService->exchangeSortNum($request->input('id'), $request->input('type'), $conditions);
        if($result){
            $request->session()->flash('message', 'ソート順を変更しました。');
        }
        $params = [];
        if($page){
            $params['page'] = $page;
        }
        if(isset($conditions) && count($conditions)){
            $params['condition'] = $listItemsService->conditionsToQuery($conditions, true);
        }
        return redirect()->route('admin.item.list', $params);
    }

    public function select(
        ListItemsService $listItemsService,
        Request $request
    )
    {
        $condition = $listItemsService->conditionsToQuery([
            'text',
            'item_category_id',
            'maker_id',
            'is_visibles',
        ]);

        if($request->input('csv_download')){
            return redirect()->route('admin.item.export', ['condition' => $condition]);
        } else {
            return redirect()->route('admin.item.list', ['condition' => $condition]);
        }
    }

    public function batch(
        BatchItemsService $batchItemsService,
        Request $request,
        $condition = null
    ) {
        try {
            $count = $batchItemsService->batch();

            switch ($request->input('action')) {
                case 'delete':
                    $request->session()->flash('message', sprintf('%s 件の商品を削除しました。', number_format($count)));
                    break;
                case 'show':
                    $request->session()->flash('message', sprintf('%s 件の商品を「表示」しました。', number_format($count)));
                    break;
                case 'hide':
                    $request->session()->flash('message', sprintf('%s 件の商品を「非表示」にしました。', number_format($count)));
                    break;
                default:
                    break;
            }
        } catch (Throwable $exception) {
            throw $exception;
        }

        return redirect()->route('admin.item.list', ['condition' => $condition]);
    }

    public function create(
        CreateItemService $createItemService,
        ListMakersService $listMakersService,
        ListItemCategoriesService $listItemCategoriesService,
        ListRegionsService $listRegionsService,
        Request $request
    )
    {
        $request->flash();

        $makers = $listMakersService->list();
        $item_categories = $listItemCategoriesService->rootList();
        $tree_json = $listItemCategoriesService->getJsTreeJson($item_categories);
        $labels = Label::toArray();

        return response()->view('admins.items.edit', compact(
            'makers',
            'tree_json',
            'labels',
        ));
    }

    public function store(
        CreateItemService $createItemService,
        ListMakersService $listMakersService,
        ListItemCategoriesService $listItemCategoriesService,
        ListRegionsService $listRegionsService,
        Request $request
    )
    {
        $errors = new MessageBag;

        $action = $request->input('action');
        if (false === array_search($action, ['confirm', 'save', 'return'])) {
            abort(500, sprintf('Invalid action. action: %s', $action));
        }

        $viewPath = 'admins.items.edit';
        $is_confirm = false;
        if ($createItemService->passesValidation()) {
            if ('confirm' === $action) {
                $viewPath = 'admins.items.confirm';
                $is_confirm = true;
            } elseif ('save' === $action) {
                try {
                    $item = $createItemService->create();
                    $request->session()->flash('message', '商品を保存しました。');
                } catch (Throwable $exception) {
                    throw $exception;
                }

                return redirect()->route('admin.item.list');
            }
        } else {
            $errors = $createItemService->getValidationMessages();
        }

        $request->flash();

        $makers = $listMakersService->list();
        $item_categories = $listItemCategoriesService->rootList();
        $tree_json = $listItemCategoriesService->getJsTreeJson($item_categories, (array)$request->input('item_category_ids'), $is_confirm);
        $labels = Label::toArray();

        return response()->view($viewPath, compact(
            'makers',
            'tree_json',
            'labels',
            'errors'
        ));
    }

    public function show(
        UpdateItemService $updateItemService,
        ListMakersService $listMakersService,
        ListItemCategoriesService $listItemCategoriesService,
        ListRegionsService $listRegionsService,
        Request $request,
        int $id
    ) {
        $request->flash();

        $makers = $listMakersService->list();
        $item_categories = $listItemCategoriesService->rootList();
        $tree_json = $listItemCategoriesService->getJsTreeJson($item_categories, (array)$request->input('item_category_ids'), true);
        $labels = Label::toArray();

        return response()->view('admins.items.show', compact(
            'makers',
            'tree_json',
            'labels',
            'id',
        ));
    }

    public function edit(
        UpdateItemService $updateItemService,
        ListMakersService $listMakersService,
        ListItemCategoriesService $listItemCategoriesService,
        ListRegionsService $listRegionsService,
        Request $request,
        int $id
    ) {
        $request->flash();

        $makers = $listMakersService->list();
        $item_categories = $listItemCategoriesService->rootList();
        $tree_json = $listItemCategoriesService->getJsTreeJson($item_categories, (array)$request->input('item_category_ids'));
        $labels = Label::toArray();

        return response()->view('admins.items.edit', compact(
            'makers',
            'tree_json',
            'labels',
            'id'
        ));
    }

    public function update(
        UpdateItemService $updateItemService,
        ListMakersService $listMakersService,
        ListItemCategoriesService $listItemCategoriesService,
        ListRegionsService $listRegionsService,
        Request $request,
        int $id
    ) {
        $errors = new MessageBag;

        $action = $request->input('action');
        if (false === array_search($action, ['confirm', 'save', 'return'])) {
            abort(500, sprintf('Invalid action. action: %s', $action));
        }

        $viewPath = 'admins.items.edit';
        $is_confirm = false;
        if ($updateItemService->passesValidation()) {
            if ('confirm' === $action) {
                $viewPath = 'admins.items.confirm';
                $is_confirm = true;
            } elseif ('save' === $action) {
                try {
                    $item = $updateItemService->update();
                    $request->session()->flash('message', '商品を保存しました。');
                } catch (Throwable $exception) {
                    throw $exception;
                }

                return redirect()->route('admin.item.list');
            }
        } else {
            $errors = $updateItemService->getValidationMessages();
        }

        $request->flash();

        $makers = $listMakersService->list();
        $item_categories = $listItemCategoriesService->rootList();
        $tree_json = $listItemCategoriesService->getJsTreeJson($item_categories, (array)$request->input('item_category_ids'), $is_confirm);
        $labels = Label::toArray();

        return response()->view($viewPath, compact(
            'makers',
            'tree_json',
            'labels',
            'id',
            'errors',
        ));
    }

    public function destroy(
        DeleteItemService $deleteItemService,
        Request $request,
        int $id
    ) {
        try {
            $item = $deleteItemService->delete();
            $request->session()->flash('message', sprintf('「%s」を削除しました。', $item->name));

        } catch (Throwable $exception) {
            throw $exception;
        }

        return redirect()->route('admin.item.list');
    }

    public function export(
        ListItemsService $listItemsService,
        $condition = null
    ) {
        return new ItemsExport($listItemsService, $condition);
    }

    public function import(
        CreateItemService $createItemService,
        UpdateItemService $updateItemService,
        SyncItemsService $syncItemsService,
        Request $request
    ) {
        if(0 === strpos(PHP_OS, 'WIN')) {
            setlocale(LC_CTYPE, 'C');
        }

        $csv_file_key = 'csv_file';

        $import_file = $request->file($csv_file_key);

        $type = $import_file->getClientOriginalExtension();

        if ($type !== 'csv') {
            $request->session()->flash('message', "CSVではありません。");

        } else {
            $file = fopen("$import_file", 'r');
            $numcols = [];
            while (($line = fgetcsv($file)) !== FALSE) {
                array_push($numcols, count($line));
            }
            fclose($file);

            $itemsImport = new ItemsImport($createItemService, $updateItemService, $request, $numcols);

            //アップロード時は1行目の内容を確認し、重複したカラム名があった場合はエラーとなるようにしてください。
            $headings = (new HeadingRowImport)->toArray($import_file);
            $headings = $headings[0][0];
            $headings = array_filter($headings, function($value) {
                return !is_null($value);
            });

            $headings_count = array_count_values($headings);
            $heading_duplicated = [];
            foreach($headings_count as $key => $count) {
                if ($count > 1) {
                    array_push($heading_duplicated, "ヘッダ「{$key}」の重複があります。");
                }
            }

            //20個を超えるスペックの登録がある場合もエラーとなるようにしてください。
            $spec_title_count = 0;
            foreach($headings as $column) {
                if (strpos($column, 'スペックタイトル') === 0) {
                    $spec_title_count++;
                }
            }

            $max_spec_title_count = 20;
            if (!empty($heading_duplicated)) {
                $errors = new HTMLString(implode("<br/>", $heading_duplicated));
                $request->session()->flash('message', $errors);
            } else if ($spec_title_count > $max_spec_title_count) {
                $request->session()->flash('message', "{$max_spec_title_count}個を超えるスペックの登録");
            } else {
                try{
                    Excel::import($itemsImport, $import_file);
                }catch ( ImportValidationException $e ){
                    $itemsImport->checkUnique();
                    if ($itemsImport->hasUniqueErrors()) {
                        $errors = $itemsImport->getUniqueErrorMessages();
                        $request->session()->flash('message', $errors);
                    } else if ($itemsImport->hasValidationErrors()) {
                        $errors = $itemsImport->getValidationErrorMessages();
                        $request->session()->flash('message', $errors);
                    } else {
                        $itemsImport->upsert();
                        $syncItemsService->sync($itemsImport->getImportJans());
                        $request->session()->flash('message', 'CSVで一括登録しました。');
                    }
                }
            }
        }

        return redirect()->route('admin.item.list', ['condition' => null]);

    }
}
