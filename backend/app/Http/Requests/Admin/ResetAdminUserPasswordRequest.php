<?php
declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ResetAdminUserPasswordRequest extends FormRequest
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
            'email' => [
                'required',
                'string',
                Rule::exists('admin_users')->where(function ($query) {
                    if (request()->offsetExists('token')) {
                        return $query->where('token', '=', (string) request()->offsetGet('token'));
                    }

                    return $query;
                }),
            ],
            'password' => [
                'sometimes',
                'required',
                'alpha_num',
                'min:8',
                'string',
                'confirmed',
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
            'email.required'
                => 'メールアドレスを入力してください。',
            'email.string'
                => 'メールアドレスは文字列で入力してください。',
            'email.exists'
                => 'メールアドレスが正しくありません。',

            'password.required'
                => 'パスワードを入力してください。',
            'password.alpha_num'
                => 'パスワードは半角英数字8文字以上で入力してください。',
            'password.min'
                => 'パスワードは半角英数字8文字以上で入力してください。',
            'password.string'
                => 'パスワードは文字列で入力してください。',
            'password.confirmed'
                => 'パスワードが一致しません。',
        ];
    }
}
