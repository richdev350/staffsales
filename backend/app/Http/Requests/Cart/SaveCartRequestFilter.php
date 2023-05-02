<?php
declare(strict_types=1);

namespace App\Http\Requests\Cart;

use App\Http\Requests\RequestFilter;

class SaveCartRequestFilter extends RequestFilter
{
    public function filterInputs(array $inputs): array
    {
        $inputs = parent::filterInputs($inputs);

        foreach ($inputs as $attribute => $value) {
            switch ($attribute) {
                case 'item_id':
                case 'amount':
                    if(is_null($value) || '' === $value){
                    break;
                    }
                    $value = intval($value);
                    break;
                default:
                    break;
            }
            $inputs[$attribute] = $value;
        }

        return $inputs;
    }
}
