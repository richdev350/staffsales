<?php
declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AuthAdminUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'login_id' => [
                'required',
                'string',
            ],
            'password' => [
                'required',
                'string',
            ],
            'login' => [
                'throttle:\App\Services\Admin\AuthenticateAdminUserService',
                'authentication:admin_users,login_id,password,,1',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'login_id.required'
                => 'ログインIDを入力してください。',
            'login_id.string'
                => 'ログインIDは文字列で入力してください。',

            'password.required'
                => 'パスワードを入力してください。',
            'password.string'
                => 'パスワードは文字列で入力してください。',

            'login.throttle'
                => '連続してログインに失敗したため、しばらくの間ログインできません。もう少し経ってから再度お試しください。',
            'login.authentication'
                => 'ログインIDまたはパスワードが違います。',
        ];
    }
}
