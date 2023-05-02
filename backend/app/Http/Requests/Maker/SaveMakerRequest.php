<?php
declare(strict_types=1);

namespace App\Http\Requests\Maker;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveMakerRequest extends FormRequest
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
                'max:255',
                'string',
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required'
                => '名称を入力してください。',
            'name.string'
                => '名称は文字列で入力してください。',
            'name.max'
                => '名称は255文字以内で入力してください。',
       ];
    }
}
