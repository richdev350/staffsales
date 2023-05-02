<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use App\Services\Admin\AuthenticateAdminUserService;
use Spatie\Permission\Exceptions\UnauthorizedException;

class RoleAuthenticatedUser
{
    /**
     * Handle an incoming request.
     *
     */
    public function handle($request, Closure $next, $role)
    {
        $isAdminAuthenticated = AuthenticateAdminUserService::isAuthenticated();
        if(!$isAdminAuthenticated){
            // ログインしていない場合はログイン画面へ
            return redirect()->route('admin.auth.login');
        }
        $authenticatedAdminUser = AuthenticateAdminUserService::getAuthenticatedUserEntity();

        $roles = is_array($role)
            ? $role
            : explode('|', $role);

        foreach ($roles as $role) {
            if ($authenticatedAdminUser->hasrole($role)) {
                return $next($request);
            }
        }
        // パーミッションが無い場合はダッシュボードへ
        // 管理者、マネージャーは店舗画面へのアクセス不可
        return redirect()->intended(route('admin.home', [], false));
    }
}
