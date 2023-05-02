<?php
declare(strict_types=1);

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveShopRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => [
                'required',
                'max:255',
                'string',
            ],
            'name' => [
                'required',
                'max:255',
                'string',
            ],
            'zip_code' => [
                'regex:/^[0-9]{7}$/',
            ],
            'prefecture_id' => [
                'nullable',
                'integer',
                'exists:prefectures,id',
            ],
            'city' => [
                'max:255',
                'string',
            ],
            'address' => [
                'max:255',
                'string',
            ],
            'tel' => [
                'regex:/^0\d{9,10}$/',
            ],
            'manager_id' => [
                'required',
                'exists:admin_users,id',
            ],
            'staff_id' => [
                'required',
                'exists:admin_users,id',
            ],
        ];
    }

    public function messages()
    {
        return [
            'code.required'
                => 'コードを入力してください。',
            'code.string'
                => 'コードは文字列で入力してください。',
            'code.max'
                => 'コードは255文字以内で入力してください。',

            'name.required'
                => '店舗名を入力してください。',
            'name.string'
                => '店舗名は文字列で入力してください。',
            'name.max'
                => '店舗名は255文字以内で入力してください。',

            'zip_code.required'
                => '郵便番号を入力してください。',
            'zip_code.regex'
                => '郵便番号が正しくありません。',

            'prefecture_id.required'
                => '都道府県を選択してください。',
            'prefecture_id.integer'
                => '都道府県が正しくありません。',
            'prefecture_id.exists'
                => '都道府県が正しくありません。',

            'city.required'
                => '市区町村を入力してください。',
            'city.string'
                => '市区町村は文字列で入力してください。',
            'city.max'
                => '市区町村は255文字以内で入力してください。',

            'address.required'
                => '住所を入力してください。',
            'address.string'
                => '住所は文字列で入力してください。',
            'address.max'
                => '住所は255文字以内で入力してください。',

            'tel.required'
                => '電話番号を入力してください。',
            'tel.regex'
                => '電話番号が正しくありません。',

            'manager_id.required'
                => '管理ユーザを選択してください。',
            'manager_id.exists'
                => '管理ユーザが正しくありません。',

            'staff_id.required'
                => '店舗ユーザを選択してください。',
            'staff_id.exists'
                => '店舗ユーザが正しくありません。',
        ];
    }
}
