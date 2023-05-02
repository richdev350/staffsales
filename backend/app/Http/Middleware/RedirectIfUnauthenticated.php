<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Session;
use App\Services\Admin\AuthenticateAdminUserService;

class RedirectIfUnauthenticated
{
    /**
     * Handle an incoming request.
     *
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // 未認証ユーザーのリダイレクト
        $is_admin_login = AuthenticateAdminUserService::isAuthenticated();
        if ('admin' == $guard && ! $is_admin_login) {
            Session::put('url.intended', $request->url());
            return redirect()->route('admin.auth.login');
        }elseif ('shop' == $guard && ! $is_admin_login) {
            Session::put('url.intended', $request->url());
            return redirect()->route('admin.auth.login');
        }
        $app = Application::getInstance();

        $login_admin_user = AuthenticateAdminUserService::getAuthenticatedUserEntity();

        // 認証済みユーザーをアプリケーションコンテナにセットしておく
        $app->offsetSet('login_admin_user', $login_admin_user);

        // 共通で使う変数をビューにアサインしておく
        view()->share(compact(
            'is_admin_login',
            'login_admin_user'
        ));
        return $next($request);
    }
}
