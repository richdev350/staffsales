<?php
declare(strict_types=1);

namespace App\Http\Requests\ItemCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Entities\ItemCategory;

class CreateItemCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'parent_id' => [
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
            'parent_id.required'
                => '親カテゴリIDを入力してください。',
            'parent_id.integer'
                => '親カテゴリIDは数値で入力してください。',
            'name.required'
                => 'カテゴリ名を入力してください。',
            'name.string'
                => 'カテゴリ名は文字列で入力してください。',
        ];
    }
}
