<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use App\Services\Admin\AuthenticateAdminUserService;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // 認証済みユーザーのリダイレクト
        $is_admin_login = AuthenticateAdminUserService::isAuthenticated();
        if ('admin' == $guard && $is_admin_login) {
            return redirect()->intended(route('admin.home', [], false));
        }elseif ('shop' == $guard && $is_admin_login) {
            return redirect()->intended(url('home'));
        }

        // 共通で使う変数をビューにアサインしておく
        view()->share(compact(
            'is_admin_login',
        ));

        return $next($request);
    }
}
