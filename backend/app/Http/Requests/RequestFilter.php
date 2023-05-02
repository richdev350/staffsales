<?php
declare(strict_types=1);

namespace App\Http\Requests;

class RequestFilter
{
    /**
     * 入力をフィルタリングして返す
     *
     * @param  array  $inputs
     * @return array
     * @note   各フォームごとのフィルタリング実装は継承先のサブクラスで行う
     */
    public function filterInputs(array $inputs): array
    {
        foreach ($inputs as $attribute => $value) {
            if (is_null($value)) {
                continue;
            }
            if (is_string($value)) {
                $value = $this->trimAsciiControlCharsAndSpaces($value);
            }

            $inputs[$attribute] = $value;
        }

        return $inputs;
    }

    /**
     * ASCII制御文字と空白文字を取り除いて返す
     *
     * @param  string  $value
     * @return string
     */
    protected function trimAsciiControlCharsAndSpaces(string $value): string
    {
        $value = trim_ascii_control_chars($value);
        $value = trim($value);
        $value = preg_replace('/^[　]+/u', '', $value);
        $value = preg_replace('/[　]+$/u', '', $value);

        return $value;
    }
}
