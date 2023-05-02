<?php
declare(strict_types=1);

namespace App\Http\Requests\Shop;

use App\Http\Requests\RequestFilter;

class SaveShopRequestFilter extends RequestFilter
{
    public function filterInputs(array $inputs): array
    {
        $inputs = parent::filterInputs($inputs);

        $encoding = mb_internal_encoding();

        foreach ($inputs as $attribute => $value) {
            switch ($attribute) {
                case 'name':
                case 'city':
                case 'address':
                    $value = mb_convert_kana((string) $value, 'a', $encoding);
                    break;
                case 'zip_code':
                case 'city':
                case 'address':
                case 'tel':
                    if (is_null($value)) {
                        $value = '';
                    }
                    if (is_string($value)) {
                        $value = mb_convert_kana((string) $value, 'a', $encoding);
                        $value = str_replace(['-','ー','―','－','/'], '', $value);
                    }
                    break;
                case 'prefecture_id':
                    if (empty($value)) {
                        $value = null;
                    }
                    break;
                default:
                    break;
            }
            $inputs[$attribute] = $value;
        }

        return $inputs;
    }
}
