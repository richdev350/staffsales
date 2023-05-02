<?php
declare(strict_types=1);

namespace App\Http\Requests\Publish;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Entities\Publish;
use Carbon\Carbon;

class SavePublishRequest extends FormRequest
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
            'exhibit_date' => [
                'required',
                'nullable',
                'string',
                'before:sales_start_date',
            ],
            'sales_start_date' => [
                'required',
                'nullable',
                'string',
                'after:exhibit_date',
            ],
            'end_of_sale_date' => [
                'required',
                'nullable',
                'string',
                'after:sales_start_date',
            ],
            'created_at' => [
                'max:255',
                'timestamp',
            ],
            'updated_at' => [
                'max:255',
                'timestamp',
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required'
            => 'タイトル名を入力してください。',
            'name.string'
            => 'タイトル名は文字列で入力してください。',
            'name.max'
            => 'タイトル名は255文字以内で入力してください。',
            'exhibit_date.required'
            => '公開開始日時を入力してください。',
            'sales_start_date.required'
            => '販売開始日時を入力してください。',
            'end_of_sale_date.required'
            => '販売終了日時を入力してください。',
            'exhibit_date.before'
            => '公開開示日時には販売開始日時より前の日時を入力してください。',
            'sales_start_date.after'
            => '販売開始日時には公開開示日時より先の日時を入力してください。',
            'end_of_sale_date.after'
            => '販売終了日時には販売開始日時より先の日時を入力してください。',
        ];
    }
}
