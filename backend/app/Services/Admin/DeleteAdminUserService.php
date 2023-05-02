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
use App\Services\Admin\AuthenticateAdminUserService;

/**
 * 管理ユーザー削除サービス
 */
class DeleteAdminUserService
{
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
    }

    /**
     * 削除処理
     *
     */
    public function delete($inputs = null): AdminUser
    {
        try {
            return DB::transaction(function () {
                $admn_user = $this->repository->find((int) $this->request->offsetGet('id'));

                $authenticateAdminUserService = Application::getInstance()->make(AuthenticateAdminUserService::class);
                $authenticatedAdminUser = $authenticateAdminUserService->getAuthenticatedUserEntity();
                if ($authenticatedAdminUser->id != $admn_user->id) {
                    $this->repository->delete($admn_user);
                    return $this->repository->findOnlyTrashed((int) $this->request->offsetGet('id'));
                }

                return $admn_user;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}
