<?php
declare(strict_types=1);

namespace App\Http\Requests\DesiredTime;

use App\Http\Requests\RequestFilter;

class SaveDesiredTimeRequestFilter extends RequestFilter
{
    public function filterInputs(array $inputs): array
    {
        $inputs = parent::filterInputs($inputs);

        foreach ($inputs as $attribute => $value) {
            switch ($attribute) {
                case 'from':
                case 'to':
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
