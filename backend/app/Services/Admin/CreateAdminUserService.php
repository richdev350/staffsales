<?php
declare(strict_types=1);

namespace App\Services\Admin;

use Throwable;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Entities\AdminUser;
use App\Models\Repositories\Contracts\AdminUserRepositoryInterface;
use App\Http\Requests\Admin\SaveAdminUserRequestFilter;
use App\Http\Requests\Admin\SaveAdminUserRequest;
use App\Services\Traits\Filterable;
use App\Services\Traits\Validatable;

/**
 * 管理ユーザー作成サービス
 */
class CreateAdminUserService
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
    }

    /**
     * 新規作成処理
     *
     */
    public function create($inputs = null): AdminUser
    {
        if (is_null($inputs)) {
            $inputs = $this->request->except('action');
        }

        try {
            return DB::transaction(function () use ($inputs) {
                $admn_user = $this->repository->new($inputs);

                $admn_user = $this->repository->persist($admn_user);

                $admn_user->assignRole($inputs['role']);

                return $admn_user;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}
