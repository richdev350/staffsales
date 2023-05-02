<?php
declare(strict_types=1);

namespace App\Http\Requests\Item;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Entities\Item;

class SaveItemRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'jan' => [
                'required',
                'string',
                Rule::unique('items')->where(function ($query) {
                    if (request('id')) {
                        return $query->where('id', '!=', (int) request('id'))
                            ->whereNull('deleted_at');
                    }else{
                        return $query->whereNull('deleted_at');
                    }
                }),
            ],
            'name' => [
                'required',
                'string',
            ],
            'maker_id' => [
                'nullable',
                'integer',
                'exists:makers,id,deleted_at,NULL'
            ],
            'item_category_ids' => [
                'required',
            ],
            'item_category_ids.*' => [
                'required',
                'integer',
                'exists:item_categories,id,deleted_at,NULL'
            ],
            'abridge' => [
                'nullable',
                'string',
            ],
            'summary' => [
                'nullable',
                'string',
            ],
            'description_title' => [
                'nullable',
                'string',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'self_medication' => [
                Rule::in([0, 1]),
            ],
            'tags' => [
                'nullable',
                'string',
            ],
            'price' => [
                'required',
                'integer',
            ],
            'is_stock' => [
                'required',
                Rule::in([0, 1]),
            ],
            'notes' => [
                'nullable',
                'string',
            ],
            'spec' => [
                'nullable',
            ],
            'spec.*.title' => [
                'required',
                'string'
            ],
            'spec.*.body' => [
                'required',
                'string'
            ],
            'max_amount' => [
                'required',
                'integer',
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required'
                => '商品名を入力してください。',
            'name.string'
                => '商品名は文字列で入力してください。',
            'jan.required'
                => 'JANコードを入力してください。',
            'jan.string'
                => 'JANコードは文字列で入力してください。',
            'jan.unique'
                => 'JANコードの重複があります。',
            'maker_id.required'
                => 'メーカーIDを入力してください。',
            'maker_id.integer'
                => 'メーカーIDは数値で入力してください。',
            'maker_id.exists'
                => 'メーカーIDはデータベースに存在する値を入力してください。',
            'item_category_ids.required'
                => '商品カテゴリIDを入力してください。',
            'item_category_ids.*.required'
                => '商品カテゴリIDを入力してください。',
            'item_category_ids.*.integer'
                => '商品カテゴリIDは数値で入力してください。',
            'item_category_ids.*.exists'
                => '商品カテゴリIDはデータベースに存在する値を入力してください。',
            'comment.required'
                => '商品説明を入力してください。',
            'comment.string'
                => '商品説明は文字列で入力してください。',
            'price.required'
                => '価格を入力してください。',
            'price.integer'
                => '価格は数値で入力してください。',
            'is_stock.required'
                => '在庫確認を選択してください。',
            'is_stock.in'
                => '在庫確認が不正です。',
            'notes.string'
                => '特記事項は文字列で入力してください。',
            'spec.*.title.required'
                => 'スペックタイトルを入力してください。',
            'spec.*.title.string'
                => 'スペックタイトルは文字列で入力してください。',
            'spec.*.body.required'
                => 'スペック内容を入力してください。',
            'spec.*.body.string'
                => 'スペック内容は文字列で入力してください。',
            'max_amount.required'
                => '最大注文数量を入力してください。',
            'max_amount.integer'
                => '最大注文数量は数値で入力してください。',
            'self_medication.required'
                => 'セルフメディケーションを選択してください。',
            'self_medication.in'
                => 'セルフメディケーションが不正です。',
            'tags.string'
                => 'タグは文字列で入力してください。',
            'abridge.string'
                => '一覧用商品要約は文字列で入力してください。',
            'summary.string'
                => '商品概要は文字列で入力してください。',
            'description_title.string'
                 => '商品説明のタイトルは文字列で入力してください。',
            'description.string'
                => '商品説明は文字列で入力してください。',
        ];
    }
}
