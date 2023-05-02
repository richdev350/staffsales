<?php
declare(strict_types=1);

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Validator as BaseValidator;

/**
 * カスタムバリデータ
 *
 * NOTE: このクラスには汎用的なもののみを実装。コンテンツ固有など個別的なものはトレイトで実装する。
 */
class Validator extends BaseValidator
{
    public function __construct($translator, $data, $rules, $messages = [])
    {
        parent::__construct($translator, $data, $rules, $messages);

        // 暗黙の拡張に追加
        // $this->implicitRules[] = 'hogehoge';
    }

    /**
     * ログイン認証の検証
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */
    public function validateAuthentication($attribute, $value, $parameters)
    {
        // 他のエラーが無かった場合にのみ検証するので、他にエラーがあればtrueを返す
        if ($this->messages()->isNotEmpty()) {
            return true;
        }
        list($table, $identityAttribute, $passwordAttribute) = $parameters;
        $isRegisteredAttribute = ! empty($parameters[3]) ? $parameters[3] : '';
        $notUseSoftDelete = ! empty($parameters[4]) ? (bool) $parameters[4] : false;

        $identity = $this->getValue($identityAttribute);
        $password = $this->getValue($passwordAttribute);

        $query = DB::table($table);
        $query->where($identityAttribute, '=', $identity);
        if ($notUseSoftDelete) {
            $query->whereNull('deleted_at');
        }

        $collection = $query->get();

        if ($collection->isEmpty()) {
            return false;
        }

        $entity = $collection->first();

        if (! $notUseSoftDelete && isset($entity->deleted_at) && null != $entity->deleted_at) {
            return false;
        }

        if (! app('hash')->check($password, $entity->{$passwordAttribute})) {
            return false;
        }

        return true;
    }

    /**
     * ログインロック状態の検証
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     */
    public function validateThrottle($attribute, $value, $parameters)
    {
        list($authenticateServiceClass) = $parameters;

        if (method_exists($authenticateServiceClass, 'isLocked')) {
            return $authenticateServiceClass::isLocked() ? false : true;
        }

        return true;
    }

    /**
     * 認証済みアカウントのパスワードの検証
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */
    public function validatePasswordWithAuthenticated($attribute, $value, $parameters)
    {
        $authenticateServiceClass = array_shift($parameters);
        $authenticateService      = app()->make($authenticateServiceClass);

        // 認証済みの場合
        if ($authenticateService->isAuthenticated()) {
            $authenticatedUserEntity = $authenticateService->getAuthenticatedUserEntity();
            if (! $authenticatedUserEntity instanceof Model) {
                return false;
            }
            if (! app('hash')->check($value, $authenticatedUserEntity->{$attribute})) {
                return false;
            }
        }
        // 未認証の場合
        else {
            return false;
        }

        return true;
    }

    /**
     * validateKatakana カタカナのバリデーション
     *
     * @param string $value
     * @access public
     * @return bool
     */
    public function validateKatakana($attribute, $value, $parameters)
    {
        return (bool) preg_match('/^[ァ-ヾ 　〜ー−]+$/u', $value);
    }
}
