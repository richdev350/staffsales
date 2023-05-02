<?php
declare(strict_types=1);

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Entities\Item;
use Carbon\Carbon;

class SaveOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $date_list = [];
        $carbon_date = new Carbon(config('app.delivery_start_date'));
        do{
            $date_list[] = $carbon_date->format('Y-m-d');
            $carbon_date->addDays(1);
        }while($carbon_date->format('Y-m-d') <= config('app.delivery_end_date'));

        return [
            'name' => [
                'required',
                'max:255',
                'string',
            ],
            'staff_id' => [
                'required',
                'string',
                'regex:/^\d{5,8}$/',
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required'
            => 'お名前を入力してください。',
            'name.string'
            => 'お名前は文字列で入力してください。',
            'name.max'
            => 'お名前は255文字以内で入力してください。',

            'staff_id.required'
            => '社員番号を入力してください。',
            'staff_id.string'
            => '社員番号は文字列で入力してください。',
            'staff_id.regex'
            => '社員番号が正しくありません。',
        ];
    }
}
