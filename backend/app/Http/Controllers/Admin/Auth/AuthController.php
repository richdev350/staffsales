<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Admin\AuthenticateAdminUserService;

class AuthController extends Controller
{
    /**
     * ログイン
     *
     */
    public function login(AuthenticateAdminUserService $authenticateAdminUserService, Request $request)
    {
        $isLocked = $authenticateAdminUserService->isLocked();

        $request->flash();

        return response()->view('admins.auths.login', compact(
            'isLocked'
        ));
    }

    /**
     * ログイン認証
     *
     */
    public function authenticate(AuthenticateAdminUserService $authenticateAdminUserService, Request $request)
    {
        $isLocked = $authenticateAdminUserService->isLocked();
        if ($authenticateAdminUserService->authenticate()) {
            $login_admin_user = $authenticateAdminUserService::getAuthenticatedUserEntity();
            if($login_admin_user->can('admin_permission')){
                $redirect_name = 'admin.home';
            }elseif($login_admin_user->can('manager_permission')){
                $redirect_name = 'admin.order.list';
            }elseif($login_admin_user->can('shop_permission')){
                $redirect_name = 'home.index';
            }else{
                $redirect_name = 'admin.auth.logout';
            }

            $intended = $request->session()->get('url.intended');
            if($intended && strpos($intended, config('app.url') . '/admin') === 0){
                return redirect()->intended(route($redirect_name, [], false));
            }else{
                return redirect(route($redirect_name));
            }
        } else {
            $errors = $authenticateAdminUserService->getValidationMessages();
        }

        $request->flash();

        return response()->view('admins.auths.login', compact(
            'isLocked',
            'errors'
        ));
    }

    /**
     * ログアウト
     *
     */
    public function logout(AuthenticateAdminUserService $authenticateAdminUserService, Request $request)
    {
        $authenticateAdminUserService->clear();
        $request->session()->flash('message', 'ログアウトしました。');
        return redirect()->route('admin.auth.login');
    }
}
