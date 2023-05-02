<?php
declare(strict_types=1);

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveCartRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'item_id' => [
                'integer',
                'required',
            ],
            'amount' => [
                'integer',
                'required',
            ],
        ];
    }

    public function messages()
    {
        return [
            'item_id.required'
                => '商品IDを入力してください。',
            'item_id.integer'
                => '商品IDは数値で入力してください。',
            'amount.required'
                => '数量を入力してください。',
            'amount.integer'
                => '数量は数値で入力してください。',
        ];
    }
}
