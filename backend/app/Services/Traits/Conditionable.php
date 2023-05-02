<?php
declare(strict_types=1);

namespace App\Services\Traits;

use InvalidArgumentException;
use Illuminate\Http\Request;

/**
 * 検索条件取り扱い用トレイト
 */
trait Conditionable
{
    /**
     * GETリクエストされた検索条件をクエリ文字列から配列に変換して返す
     *
     * @param  string|null  $conditionQuery
     * @return array
     */
    public function conditionQueryToArray($conditionQuery = ''): array
    {
        if (is_null($conditionQuery) || '' === $conditionQuery) {
            return [];
        }

        //$temp = current(csv2array($conditionQuery));
        $temp = explode(",", $conditionQuery);

        $conditions = [];
        foreach ($temp as $val) {
            if (null == $val) {
                continue;
            }

            $val = urldecode($val);
            $val = change_special_char($val, false);
            $pos = stripos($val, ':');
            if (false === $pos) {
                continue;
            }

            $key   = substr($val, 0, $pos);
            $value = ltrim(stristr($val, ':'), ':');
            if (is_json($value)) {
                $value = json_decode($value);
            }
            if($key == 'clear_condition'){
                return [];
            }

            $conditions[$key] = $value;
        }

        return $conditions;
    }

    /**
     * POSTリクエストされた検索条件を配列からクエリ文字列に変換して返す
     *
     * @param  array|null  $conditionAttributes
     * @return string
     * @throws InvalidArgumentException
     */
    public function conditionsToQuery($conditionAttributes = [], $use_conditions_array = false): string
    {
        if ($this->request->filled('count')) {
            array_push($conditionAttributes, 'count');
        }
        
        if (is_null($conditionAttributes) || 0 == count($conditionAttributes)) {
            return '';
        }

        if (! $this->request instanceof Request) {
            throw new InvalidArgumentException('This service must have $request parameter of the \Illuminate\Http\Request instance.');
        }

        if($this->request->input('clear_condition')){
            return 'clear_condition:1' . ($this->request->input('count') ? ",count:" . $this->request->input('count') : "");
        }

        $conditions = [];
        if($use_conditions_array){
            $conditions = $conditionAttributes;
        }else{
            foreach ($conditionAttributes as $conditionAttribute) {
                if ($this->request->filled($conditionAttribute)) {
                    $conditions[$conditionAttribute] = $this->request->input($conditionAttribute);
                }
            }
        }

        $conditions = array_filter($conditions, function ($value) {
            if (is_null($value)) {
                return false;
            } elseif (is_string($value) && '' === trim($value)) {
                return false;
            /* 値が0の配列が除外されるためコメントアウト
            } elseif (is_array($value) && 0 == count(array_filter($value))) {
                return false;
            */
            }
            return true;
        });
        if (0 == count($conditions)) {
            return '';
        }

        $conditions = array_map(function ($value, $key) {
            if (is_array($value)) {
                $value = json_encode($value);
            }

            if (in_array($key, ['name', 'search', 'text'])) {
                $value = str_replace(',', urlencode(','), $value);
                $value = change_special_char($value);
                $value = urlencode($value);
            }

            return $key . ':' . str_replace(',', urlencode(urlencode(',')), $value);
        }, $conditions, array_keys($conditions));

        $conditionQuery = implode(',', $conditions);
        return $conditionQuery;
    }
}
