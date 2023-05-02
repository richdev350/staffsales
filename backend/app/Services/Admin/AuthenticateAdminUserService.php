<?php
declare(strict_types=1);

namespace App\Services\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Entities\AdminUser;
use App\Models\Repositories\Contracts\AdminUserRepositoryInterface;
use App\Http\Requests\Admin\AuthAdminUserRequestFilter;
use App\Http\Requests\Admin\AuthAdminUserRequest;
use App\Services\Traits\Filterable;
use App\Services\Traits\Validatable;
use App\Services\Traits\Authenticatable;

/**
 * 管理ユーザー認証サービス
 */
class AuthenticateAdminUserService
{
    use Filterable,
        Validatable,
        Authenticatable;

    /**
     * 認証関連でデータ抽出に使用するリポジトリクラス名
     *
     * @var string
     */
    protected static $repositoryClass = 'App\Models\Repositories\Contracts\AdminUserRepositoryInterface';

    /**
     * ユーザーインスタンスを保存するセッションキー
     *
     * @var string
     */
    protected static $sessionKey = 'admin_user';

    /**
     * ログインIDに使用する属性名
     *
     * @var string
     */
    protected static $identityAttribute = 'login_id';

    /**
     * ログインパスワードに使用する属性名
     *
     * @var string
     */
    protected static $passwordAttribute = 'password';

    /**
     * ログインボタン属性名
     *
     * @var string
     */
    protected static $loginAttribute = 'login';

    /**
     * ログイン試行を何秒間監視するか
     *
     * @var int
     */
    protected static $observateDuring = 300;

    /**
     * 許容するログイン試行最大回数
     *
     * @var int
     */
    protected static $maxLoginAttempts = 5;

    /**
     * ログインを何秒間ロックするか
     *
     * @var int
     */
    protected static $lockoutTime = 60;

    /**
     * 認証をロックされているかどうか
     *
     * @var bool
     */
    protected static $locked = false;

    /**
     * リポジトリ
     */
    protected $repository;

    /**
     * @var Request
     */
    protected $request;

    /**
     * コンストラクタ
     *
     */
    public function __construct(AdminUserRepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request    = $request;

        $this->setRequestFilter(new AuthAdminUserRequestFilter());
        $this->setFormRequest(new AuthAdminUserRequest());
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
}
