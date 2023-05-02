<?php
declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Http\Requests\RequestFilter;

class ResetAdminUserPasswordRequestFilter extends RequestFilter
{
    /**
     * 入力をフィルタリングして返す
     *
     * @param  array  $inputs
     * @return array
     */
    public function filterInputs(array $inputs): array
    {
        $inputs = parent::filterInputs($inputs);

        $encoding = mb_internal_encoding();

        foreach ($inputs as $attribute => $value) {
            switch ($attribute) {
                case 'email':
                case 'password':
                case 'password_confirmation':
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
