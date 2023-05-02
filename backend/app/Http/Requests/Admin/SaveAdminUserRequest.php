<?php
declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Entities\Role;

class SaveAdminUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'max:255',
                'string',
            ],
            'login_id' => [
                'required',
                'max:255',
                'string',
                Rule::unique('admin_users')->where(function ($query) {
                    if (request()->offsetExists('id')) {
                        return $query->where('id', '!=', (int) request()->offsetGet('id'));
                    }

                    return $query;
                }),
            ],
            'email' => [
                'required',
                'max:255',
                'string',
                'email',
                Rule::unique('admin_users')->where(function ($query) {
                    if (request()->offsetExists('id')) {
                        return $query->where('id', '!=', (int) request()->offsetGet('id'));
                    }

                    return $query;
                }),
            ],
            'password' => [
                'required_if:is_create,' . true,
                'string',
                'regex:/^[a-zA-Z0-9!-\/:-@¥[-`{-~]{8,}$/',
            ],
            'role' => [
                'required',
                'string',
                'exists:roles,name',
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
            'name.required'
                => '名前を入力してください。',
            'name.string'
                => '名前は文字列で入力してください。',
            'name.max'
                => '名前は255文字以内で入力してください。',

            'login_id.required'
                => 'ログインIDを入力してください。',
            'login_id.string'
                => 'ログインIDは文字列で入力してください。',
            'login_id.max'
                => 'ログインIDは255文字以内で入力してください。',
            'login_id.unique'
                => '登録できないログインIDです。',

            'email.required'
                => 'メールアドレスを入力してください。',
            'email.string'
                => 'メールアドレスは文字列で入力してください。',
            'email.max'
                => 'メールアドレスは255文字以内で入力してください。',
            'email.email'
                => 'メールアドレスが正しくありません。',
            'email.unique'
                => '登録できないメールアドレスです。',

            'password.required_if'
                => 'パスワードを入力してください。',
            'password.string'
                => 'パスワードは文字列で入力してください。',
            'password.regex'
                => 'パスワードは半角英数記号8文字以上で入力してください。',

            'role.required'
                => '権限を選択してください。',
            'role.integer'
                => '権限が正しくありません。',
            'role.exists'
                => '権限が正しくありません。',
        ];
    }
}
