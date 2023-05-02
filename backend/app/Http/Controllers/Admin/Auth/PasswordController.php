<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Http\Controllers\Controller;
use App\Services\Admin\ResetAdminUserPasswordService;
use App\Services\Admin\AuthenticateAdminUserService;

class PasswordController extends Controller
{
    /**
     * パスワード忘れ
     *
     */
    public function forgot(ResetAdminUserPasswordService $ResetAdminUserPasswordService, Request $request)
    {
        $request->flash();

        return response()->view('admins.auths.forgot_password');
    }

    /**
     * パスワード再設定URLの発行
     *
     */
    public function request(ResetAdminUserPasswordService $ResetAdminUserPasswordService, Request $request)
    {
        if ($ResetAdminUserPasswordService->passesValidation()) {
            try {
                $user = $ResetAdminUserPasswordService->sendResetLink();
                $request->session()->flash('message', 'パスワード再設定URLを送信しました。');
            } catch (Throwable $exception) {
                throw $exception;
            }

            return redirect()->route('admin.auth.forgot-password');
        } else {
            $errors = $ResetAdminUserPasswordService->getValidationMessages();
        }

        $request->flash();

        return response()->view('admins.auths.forgot_password', compact(
            'errors'
        ));
    }

    /**
     * パスワードリセット
     *
     */
    public function reset(
        ResetAdminUserPasswordService $ResetAdminUserPasswordService,
        AuthenticateAdminUserService $authenticateAdminUserService,
        Request $request,
        string $token
    ) {
        $errors = new MessageBag;
        if ($request->isMethod('post')) {
            if ($ResetAdminUserPasswordService->passesValidation()) {
                try {
                    $user = $ResetAdminUserPasswordService->resetPassword();
                    $request->session()->flash('message', 'パスワードをリセットしました。');
                } catch (Throwable $exception) {
                    throw $exception;
                }

                // NOTE: 自動的にログインしてダッシュボードにリダイレクト
                if ($authenticateAdminUserService->login($user)) {
                    return redirect()->route('admin.home');
                }
            } else {
                $errors = $ResetAdminUserPasswordService->getValidationMessages();
            }
        }

        $request->flash();

        return response()->view('admins.auths.reset_password', compact(
            'token',
            'errors'
        ));
    }
}
