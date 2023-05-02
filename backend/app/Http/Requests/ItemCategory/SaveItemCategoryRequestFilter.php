<?php
declare(strict_types=1);

namespace App\Http\Requests\ItemCategory;

use App\Http\Requests\RequestFilter;

class SaveItemCategoryRequestFilter extends RequestFilter
{
    public function filterInputs(array $inputs): array
    {
        $inputs = parent::filterInputs($inputs);

        $encoding = mb_internal_encoding();

        foreach ($inputs as $attribute => $value) {
            switch ($attribute) {
                case 'id':
                    $value = intval($value);
                    break;
                case 'name':
                    $value = mb_convert_kana((string) $value, 'a', $encoding);
                    break;
                default:
                    break;
            }
            $inputs[$attribute] = $value;
        }

        return $inputs;
    }
}
