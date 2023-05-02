<?php
declare(strict_types=1);

namespace App\Http\Requests\ItemCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Entities\ItemCategory;

class SaveItemCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => [
                'required',
                'integer',
            ],
            'name' => [
                'required',
                'string',
            ],
        ];
    }

    public function messages()
    {
        return [
            'id.required'
                => 'カテゴリIDを入力してください。',
            'id.integer'
                => 'カテゴリIDは数値で入力してください。',
            'name.required'
                => 'カテゴリ名を入力してください。',
            'name.string'
                => 'カテゴリ名は文字列で入力してください。',
        ];
    }
}
