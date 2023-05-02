<?php
declare(strict_types=1);

namespace App\Services\Admin;

use Throwable;
use Exception;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Entities\AdminUser;
use App\Models\Repositories\Contracts\AdminUserRepositoryInterface;
use App\Http\Requests\Admin\SaveAdminUserRequestFilter;
use App\Http\Requests\Admin\SaveAdminUserRequest;
use App\Services\Admin\AuthenticateAdminUserService;
use App\Services\Traits\Filterable;
use App\Services\Traits\Validatable;

/**
 * 管理ユーザー更新サービス
 */
class UpdateAdminUserService
{
    use Filterable,
        Validatable;

    /**
     * リポジトリ
     */
    private $repository;

    /**
     * @var Request
     */
    private $request;

    /**
     * コンストラクタ
     *
     */
    public function __construct(AdminUserRepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request    = $request;

        $this->setRequestFilter(new SaveAdminUserRequestFilter());
        $this->setFormRequest(new SaveAdminUserRequest());
        $this->init();
    }

    /**
     * 初期化
     *
     */
    public function init()
    {
        if (! $this->request->isMethod('GET')) {
            $this->filterInputs();
            return;
        }

        $this->request->flush();

        $admn_user = $this->repository->find((int) $this->request->offsetGet('id'));

        $defaults = [
            'name' => $admn_user->name,
            'login_id'=> $admn_user->login_id,
            'email'=> $admn_user->email,
            'role' => $admn_user->getRoleNames()[0],
        ];

        $this->request->merge($defaults);
    }

    /**
     * 更新処理
     *
     */
    public function update($inputs = null): AdminUser
    {
        if (is_null($inputs)) {
            $inputs = $this->request->except('action');
        }
        if (empty($inputs['password'])) {
            unset($inputs['password']);
        }

        try {
            return DB::transaction(function () use ($inputs) {
                $admn_user = $this->repository->find((int) $this->request->offsetGet('id'));
                $admn_user = $this->repository->edit($admn_user, $inputs);

                $admn_user = $this->repository->persist($admn_user);

                $admn_user->syncRoles($inputs['role']);

                // 認証済みユーザー自身の更新の場合は、データ変更後はセッション保存の認証済みユーザーをデータベースから取得し直したモデルで再セット
                $authenticateAdminUserService = Application::getInstance()->make(AuthenticateAdminUserService::class);
                $authenticatedUser = $authenticateAdminUserService->getAuthenticatedUserEntity();
                if ($authenticatedUser->id == $admn_user->id) {
                    AuthenticateAdminUserService::freshAuthenticatedUserEntity();
                }

                return $admn_user;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}
