<?php
declare(strict_types=1);

namespace App\Http\Requests\DesiredTime;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Entities\DesiredTime;

class SaveDesiredTimeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'from' => [
                'required',
                Rule::in(DesiredTime::RANGE_OF_TIMES),
            ],
            'to' => [
                'required',
                Rule::in(DesiredTime::RANGE_OF_TIMES),
                'gt:from',
            ],
        ];
    }

    public function messages()
    {
        return [
            'from.required'
                => 'FROMを入力してください。',
            'from.in'
                => 'FROMの値が不正です。',
            'to.required'
                => 'TOを入力してください。',
            'to.in'
                => 'TOの値が不正です。',
            'to.gt'
                => 'TOの値はFROMよりも大きくしてください。',
        ];
    }
}
