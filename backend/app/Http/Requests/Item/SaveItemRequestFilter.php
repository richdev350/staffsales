<?php
declare(strict_types=1);

namespace App\Http\Requests\Item;

use App\Http\Requests\RequestFilter;

class SaveItemRequestFilter extends RequestFilter
{
    public function filterInputs(array $inputs): array
    {
        $inputs = parent::filterInputs($inputs);

        $encoding = mb_internal_encoding();

        foreach ($inputs as $attribute => $value) {
            switch ($attribute) {
                case 'name':
                case 'comment':
                case 'note':
                    $value = mb_convert_kana((string) $value, 'a', $encoding);
                    break;
                case 'maker_id':
                case 'item_category_ids[]':
                case 'price':
                case 'is_stock':
                case 'max_amount':
                    if(is_null($value) || '' === $value){
                    break;
                    }
                    $value = intval($value);
                    break;
                case 'spec[]':
                    $value['title'] = mb_convert_kana((string) $value['title'], 'a', $encoding);
                    $value['body'] = mb_convert_kana((string) $value['body'], 'a', $encoding);
                    break;
                default:
                    break;
            }
            $inputs[$attribute] = $value;
        }

        return $inputs;
    }
}
