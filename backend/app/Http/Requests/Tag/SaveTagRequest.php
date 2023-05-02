<?php
declare(strict_types=1);

namespace App\Http\Requests\Tag;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Entities\Tag;

class SaveTagRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required'
                => '商品タグを入力してください。',
            'name.string'
                => '商品タグは文字列で入力してください。',
        ];
    }
}
