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
use App\Mail\Admin\AdminUserPasswordResetMail;
use App\Http\Requests\Admin\ResetAdminUserPasswordRequestFilter;
use App\Http\Requests\Admin\ResetAdminUserPasswordRequest;
use App\Services\Traits\Filterable;
use App\Services\Traits\Validatable;
use App\Services\Traits\Mailable;

/**
 * 管理ユーザーのパスワードリセットサービス
 */
class ResetAdminUserPasswordService
{
    use Filterable,
        Validatable,
        Mailable;

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

        $this->setRequestFilter(new ResetAdminUserPasswordRequestFilter());
        $this->setFormRequest(new ResetAdminUserPasswordRequest());
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

        if (false !== stristr(current_route_name(), 'reset')) {
            if (! $this->request->offsetExists('token')) {
                abort(404, 'Token not found.');
            }

            $admin_user = $this->repository->findByToken($this->request->offsetGet('token'));
            if (! $admin_user instanceof AdminUser) {
                abort(404, sprintf('Invalid token given: %s', $this->request->offsetGet('token')));
            }
        }
    }

    /**
     * パスワードリセットリンクを送信する
     *
     */
    public function sendResetLink($inputs = null): AdminUser
    {
        if (is_null($inputs)) {
            $inputs = $this->request->except('action');
        }

        try {
            return DB::transaction(function () use ($inputs) {
                $admn_user = $this->repository->findByEmail($inputs['email']);

                $admn_user->token            = $this->repository->createUniqueToken('reset_password');
                $admn_user->token_expired_at = (new Carbon())->addDay(1);

                $admn_user = $this->repository->persist($admn_user);

                $this->sendResetLinkMail($admn_user);

                return $admn_user;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }

    /**
     * パスワードリセットリンクメール送信する
     *
     */
    public function sendResetLinkMail(AdminUser $admn_user)
    {
        $mail = new AdminUserPasswordResetMail($admn_user);
        if (! $this->send($mail, [$admn_user->email])) {
            throw new Exception('パスワードリセットリンクメールの送信に失敗しました。');
        }
    }

    /**
     * パスワードリセット処理
     *
     */
    public function resetPassword($inputs = null): AdminUser
    {
        if (is_null($inputs)) {
            $inputs = $this->request->except('action');
        }

        try {
            return DB::transaction(function () use ($inputs) {
                $admn_user = $this->repository->findByToken($this->request->offsetGet('token'));
                $admn_user = $this->repository->edit($admn_user, $inputs);

                $admn_user->token            = null;
                $admn_user->token_expired_at = null;

                $admn_user = $this->repository->persist($admn_user);

                return $admn_user;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}
