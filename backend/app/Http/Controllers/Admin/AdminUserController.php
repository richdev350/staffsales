<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Http\Controllers\Controller;
use App\Models\Entities\Admin\Role;
use App\Services\Role\ListRolesService;
use App\Services\Admin\ListAdminUsersService;
use App\Services\Admin\BatchAdminUsersService;
use App\Services\Admin\CreateAdminUserService;
use App\Services\Admin\UpdateAdminUserService;
use App\Services\Admin\DeleteAdminUserService;
use App\Services\Admin\AuthenticateAdminUserService;

class AdminUserController extends Controller
{
    /**
     * 一覧画面
     *
     */
    public function list(
        ListAdminUsersService $listAdminUsersService,
        ListRolesService $listRolesService,
        AuthenticateAdminUserService $authenticateAdminUserService,
        $condition = null
    ) {
        $roles = $listRolesService->list();
        $conditions = $listAdminUsersService->conditionQueryToArray($condition);
        $paginator = $listAdminUsersService->pagination($conditions);

        return response()->view('admins.admin-users.list', compact(
            'conditions',
            'roles',
            'paginator'
        ));
    }

    /**
     * 抽出
     *
     */
    public function select(ListAdminUsersService $listAdminUsersService)
    {
        $condition = $listAdminUsersService->conditionsToQuery([
            'email',
            'name',
        ]);

        return redirect()->route('admin.admin-user.list', ['condition' => $condition]);
    }

    /**
     * 一括処理
     *
     */
    public function batch(
        BatchAdminUsersService $batchAdminUsersService,
        Request $request,
        $condition = null
    ) {
        try {
            $count = $batchAdminUsersService->batch();

            switch ($request->input('action')) {
                case 'delete':
                    $request->session()->flash('message', sprintf('%s 件のアカウントを削除しました。', number_format($count)));
                    break;
                default:
                    break;
            }
        } catch (Throwable $exception) {
            throw $exception;
        }

        return redirect()->route('admins.admin-users.list', ['condition' => $condition]);
    }

    /**
     * 新規登録画面
     *
     */
    public function create(
        CreateAdminUserService $createAdminUserService,
        ListRolesService $listRolesService,
        Request $request
    )
    {
        $roles = $listRolesService->list();

        $request->flash();

        return response()->view('admins.admin-users.edit', compact(
            'roles'
        ));
    }

    /**
     * 確認画面/新規登録
     *
     */
    public function store(
        CreateAdminUserService $createAdminUserService,
        ListRolesService $listRolesService,
        Request $request
    )
    {
        $roles = $listRolesService->list();
        $errors = new MessageBag;

        $action = $request->input('action');
        if (false === array_search($action, ['confirm', 'save', 'return'])) {
            abort(500, sprintf('Invalid action. action: %s', $action));
        }

        // NOTE: 新規登録フラグをセットしてパスワードの必須検証をONにする
        $request->merge([
            'is_create' => true,
        ]);

        $viewPath = 'admins.admin-users.edit';
        if ($createAdminUserService->passesValidation()) {
            if ('confirm' === $action) {
                $viewPath = 'admins.admin-users.confirm';
            } elseif ('save' === $action) {
                try {
                    $user = $createAdminUserService->create();
                    $request->session()->flash('message', 'アカウントを保存しました。');
                } catch (Throwable $exception) {
                    throw $exception;
                }

                return redirect()->route('admin.admin-user.list');
            }
        } else {
            $errors = $createAdminUserService->getValidationMessages();
        }

        $request->flash();

        return response()->view($viewPath, compact(
            'roles',
            'errors'
        ));
    }

    /**
     * 詳細画面
     *
     */
    public function show(
        UpdateAdminUserService $updateAdminUserService,
        Request $request,
        int $id
    ) {

        $request->flash();

        return response()->view('admins.admin-users.show', compact(
            'id'
        ));
    }

    /**
     * 編集画面
     *
     */
    public function edit(
        UpdateAdminUserService $updateAdminUserService,
        ListRolesService $listRolesService,
        Request $request,
        int $id
    ) {
        $roles = $listRolesService->list();

        $request->flash();

        return response()->view('admins.admin-users.edit', compact(
            'id',
            'roles'
        ));
    }

    /**
     * 確認画面/編集
     *
     */
    public function update(
        UpdateAdminUserService $updateAdminUserService,
        ListRolesService $listRolesService,
        Request $request,
        int $id
    ) {
        $roles = $listRolesService->list();
        $errors = new MessageBag;

        $action = $request->input('action');
        if (false === array_search($action, ['confirm', 'save', 'return'])) {
            abort(500, sprintf('Invalid action. action: %s', $action));
        }

        $viewPath = 'admins.admin-users.edit';
        if ($updateAdminUserService->passesValidation()) {
            if ('confirm' === $action) {
                $viewPath = 'admins.admin-users.confirm';
            } elseif ('save' === $action) {
                try {
                    $user = $updateAdminUserService->update();
                    $request->session()->flash('message', 'アカウントを保存しました。');
                } catch (Throwable $exception) {
                    throw $exception;
                }

                return redirect()->route('admin.admin-user.list');
            }
        } else {
            $errors = $updateAdminUserService->getValidationMessages();
        }

        $request->flash();

        return response()->view($viewPath, compact(
            'id',
            'roles',
            'errors'
        ));
    }

    /**
     * 削除
     *
     */
    public function destroy(
        DeleteAdminUserService $deleteAdminUserService,
        Request $request,
        int $id
    ) {
        try {
            $user = $deleteAdminUserService->delete();

            if($user->deleted_at){
                $request->session()->flash('message', sprintf('アカウント「%s」を削除しました。', $user->name));
            }else{
                $request->session()->flash('message', '自分自身のアカウントは削除出来ません。');
            }

        } catch (Throwable $exception) {
            throw $exception;
        }

        return redirect()->route('admin.admin-user.list');
    }
}
