<?php
declare(strict_types=1);

namespace App\Http\Requests\Publish;

use App\Http\Requests\RequestFilter;

class SavePublishRequestFilter extends RequestFilter
{
    public function filterInputs(array $inputs): array
    {
        $inputs = parent::filterInputs($inputs);

        $encoding = mb_internal_encoding();

        foreach ($inputs as $attribute => $value) {
            switch ($attribute) {
                case 'desired_date':
                    $value = str_replace(['ー', '―', '－', '/'], '-', $value);
                    $value = mb_convert_kana((string) $value, 'a', $encoding);
                    break;
                case 'tel':
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
