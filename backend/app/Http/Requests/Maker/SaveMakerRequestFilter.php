<?php
declare(strict_types=1);

namespace App\Http\Requests\Maker;

use App\Http\Requests\RequestFilter;

class SaveMakerRequestFilter extends RequestFilter
{
    public function filterInputs(array $inputs): array
    {
        $inputs = parent::filterInputs($inputs);
        $encoding = mb_internal_encoding();

        return $inputs;
    }
}
