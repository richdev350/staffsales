<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use App\Services\Admin\AuthenticateAdminUserService;
use Spatie\Permission\Exceptions\UnauthorizedException;

class PermissionAuthenticatedUser
{
    /**
     * Handle an incoming request.
     *
     */
    public function handle($request, Closure $next, $permission)
    {
        $isAdminAuthenticated = AuthenticateAdminUserService::isAuthenticated();
        if(!$isAdminAuthenticated){
            // ログインしていない場合はログイン画面へ
            return redirect()->route('admin.auth.login');
        }
        $authenticatedAdminUser = AuthenticateAdminUserService::getAuthenticatedUserEntity();

        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);

        foreach ($permissions as $permission) {
            if ($authenticatedAdminUser->can($permission)) {
                return $next($request);
            }
        }
        // パーミッションが無い場合はダッシュボードまたは商品一覧へ
        if($authenticatedAdminUser->hasRole('shop')){
            return redirect()->intended(url('home'));
        }else{
            return redirect()->intended(route('admin.home', [], false));
        }
    }
}
