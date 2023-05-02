<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Http\Controllers\Controller;
use App\Models\Entities\Admin\Prefecture;
use App\Services\Region\ListRegionsService;
use App\Services\Prefecture\ListPrefecturesService;
use App\Services\Admin\ListAdminUsersService;
use App\Services\Shop\ListShopsService;
use App\Services\Shop\BatchShopsService;
use App\Services\Shop\CreateShopService;
use App\Services\Shop\UpdateShopService;
use App\Services\Shop\DeleteShopService;

class ShopController extends Controller
{
    public function list(
        ListShopsService $listShopsService,
        ListRegionsService $listRegionsService,
        ListPrefecturesService $listPrefecturesService,
        $condition = null
    ) {
        $regions = $listRegionsService->list();
        $prefectures = $listPrefecturesService->list();
        $conditions = $listShopsService->conditionQueryToArray($condition);
        $paginator = $listShopsService->pagination($conditions);

        return response()->view('admins.shops.list', compact(
            'regions',
            'prefectures',
            'conditions',
            'paginator'
        ));
    }

    public function select(ListShopsService $listShopsService)
    {
        $condition = $listShopsService->conditionsToQuery([
            'code',
            'name',
            'region_id',
            'prefecture_id',
            'city',
        ]);

        return redirect()->route('admin.shop.list', ['condition' => $condition]);
    }

    public function batch(
        BatchShopsService $batchShopsService,
        Request $request,
        $condition = null
    ) {
        try {
            $count = $batchShopsService->batch();

            switch ($request->input('action')) {
                case 'delete':
                    $request->session()->flash('message', sprintf('%s 件を削除しました。', number_format($count)));
                    break;
                default:
                    break;
            }
        } catch (Throwable $exception) {
            throw $exception;
        }

        return redirect()->route('admin.shop.list', ['condition' => $condition]);
    }

    public function create(
        CreateShopService $createShopService,
        ListPrefecturesService $listPrefecturesService,
        ListAdminUsersService $listAdminUsersService,
        Request $request
    )
    {
        $prefectures = $listPrefecturesService->list();
        $managers = $listAdminUsersService->getListByRole('manager');
        $staffs = $listAdminUsersService->getListByRole('shop');
        $request->flash();

        return response()->view('admins.shops.edit', compact(
            'prefectures',
            'managers',
            'staffs',
        ));
    }

    public function store(
        CreateShopService $createShopService,
        ListPrefecturesService $listPrefecturesService,
        ListAdminUsersService $listAdminUsersService,
        Request $request
    )
    {
        $prefectures = $listPrefecturesService->list();
        $managers = $listAdminUsersService->getListByRole('manager');
        $staffs = $listAdminUsersService->getListByRole('shop');
        $errors = new MessageBag;

        $action = $request->input('action');
        if (false === array_search($action, ['confirm', 'save', 'return'])) {
            abort(500, sprintf('Invalid action. action: %s', $action));
        }

        $viewPath = 'admins.shops.edit';
        if ($createShopService->passesValidation()) {
            if ('confirm' === $action) {
                $viewPath = 'admins.shops.confirm';
            } elseif ('save' === $action) {
                try {
                    $shop = $createShopService->create();
                    $request->session()->flash('message', '店舗を保存しました。');
                } catch (Throwable $exception) {
                    throw $exception;
                }

                return redirect()->route('admin.shop.list');
            }
        } else {
            $errors = $createShopService->getValidationMessages();
        }

        $request->flash();

        return response()->view($viewPath, compact(
            'prefectures',
            'managers',
            'staffs',
            'errors'
        ));
    }

    public function show(
        UpdateShopService $updateShopService,
        Request $request,
        int $id
    ) {

        $request->flash();

        return response()->view('admins.shops.show', compact(
            'id'
        ));
    }

    public function edit(
        UpdateShopService $updateShopService,
        ListPrefecturesService $listPrefecturesService,
        ListAdminUsersService $listAdminUsersService,
        Request $request,
        int $id
    ) {
        $prefectures = $listPrefecturesService->list();
        $managers = $listAdminUsersService->getListByRole('manager');
        $staffs = $listAdminUsersService->getListByRole('shop');

        $request->flash();

        return response()->view('admins.shops.edit', compact(
            'id',
            'prefectures',
            'managers',
            'staffs',
        ));
    }

    public function update(
        UpdateShopService $updateShopService,
        ListPrefecturesService $listPrefecturesService,
        ListAdminUsersService $listAdminUsersService,
        Request $request,
        int $id
    ) {
        $prefectures = $listPrefecturesService->list();
        $managers = $listAdminUsersService->getListByRole('manager');
        $staffs = $listAdminUsersService->getListByRole('shop');
        $errors = new MessageBag;

        $action = $request->input('action');
        if (false === array_search($action, ['confirm', 'save', 'return'])) {
            abort(500, sprintf('Invalid action. action: %s', $action));
        }

        $viewPath = 'admins.shops.edit';
        if ($updateShopService->passesValidation()) {
            if ('confirm' === $action) {
                $viewPath = 'admins.shops.confirm';
            } elseif ('save' === $action) {
                try {
                    $shop = $updateShopService->update();
                    $request->session()->flash('message', '店舗を保存しました。');
                } catch (Throwable $exception) {
                    throw $exception;
                }

                return redirect()->route('admin.shop.list');
            }
        } else {
            $errors = $updateShopService->getValidationMessages();
        }

        $request->flash();

        return response()->view($viewPath, compact(
            'id',
            'prefectures',
            'managers',
            'staffs',
            'errors'
        ));
    }

    public function destroy(
        DeleteShopService $deleteShopService,
        Request $request,
        int $id
    ) {
        try {
            $shop = $deleteShopService->delete();
            $request->session()->flash('message', sprintf('店舗「%s」を削除しました。', $shop->name));
            
        } catch (Throwable $exception) {
            throw $exception;
        }

        return redirect()->route('admin.shop.list');
    }
}
