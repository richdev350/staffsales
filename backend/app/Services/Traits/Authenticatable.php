<?php
declare(strict_types=1);

namespace App\Services\Traits;

use InvalidArgumentException;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;
use Str;

/**
 * 認証用トレイト
 */
trait Authenticatable
{
    /**
     * ログイン
     *
     * @param  Model  $entity
     * @return bool
     */
    public function login(Model $entity): bool
    {
        Session::regenerate(true);

        Session::put(self::$sessionKey, $entity);

        self::unlock();

        return true;
    }

    /**
     * 認証
     *
     * @return bool
     */
    public function authenticate(): bool
    {
        if ($this->passesValidation()) {
            Session::regenerate(true);
            list($identity) = array_values($this->credentials());
            $method = 'findBy' . ucfirst(Str::camel(self::$identityAttribute));
            $entity = $this->repository->{$method}($identity);
            Session::put(self::$sessionKey, $entity);

            self::unlock();

            return true;
        }

        if ($this->validator->messages()->has(self::$loginAttribute) && ! $this->isLocked()) {
            $this->observe();
        }

        return false;
    }

    /**
     * 認証エラーを監視する
     *
     * @return void
     */
    public function observe()
    {
        if (! self::isObservationAvailable()) {
            return;
        }

        list($identity, $password) = array_values($this->credentials());

        $checkKeys = [
            'client_ip'           => self::$sessionKey . '_IP_' . str_replace('.', '-', get_client_ip()),
            'broute_force_attack' => self::$sessionKey . '_BFA_' . $identity,
        ];
        if (is_string($password)) {
            $checkKeys['reverse_broute_force_attack'] = self::$sessionKey . '_RBFA_' . $password;
        }

        $initialCheckData = [
            'observation_start' => time(),
            'failure_count'     => 1,
        ];

        foreach ($checkKeys as $checkKey) {
            if (Session::has($checkKey)) {
                $checkData = Session::get($checkKey);
                if ($checkData['observation_start'] + self::$observateDuring > time()) {
                    if (! self::isLocked()) {
                        $checkData['failure_count']++;
                        if (self::$maxLoginAttempts <= $checkData['failure_count']) {
                            Session::put(self::$sessionKey . '_lock_expire', time() + self::$lockoutTime);
                            self::lock();
                            break;
                        } else {
                            Session::put($checkKey, $checkData);
                        }
                    } else {
                        if (Session::has(self::$sessionKey . '_lock_expire')
                            && Session::get(self::$sessionKey . '_lock_expire') < time()
                        ) {
                            Session::put($checkKey, $initialCheckData);
                            self::unlock();
                        }
                    }
                } else {
                    Session::put($checkKey, $initialCheckData);
                }
            } else {
                Session::put($checkKey, $initialCheckData);
            }
        }
    }

    /**
     * 認証ロックされているかどうかを返す
     *
     * @return bool
     */
    public static function isLocked(): bool
    {
        if (self::isObservationAvailable()) {
            if (Session::has(self::$sessionKey . '_lock_expire')) {
                if (Session::get(self::$sessionKey . '_lock_expire') >= time()) {
                    self::lock();
                } else {
                    self::unlock();
                }
            }
        } else {
            self::unlock();
        }

        return self::$locked;
    }

    /**
     * 認証ロックする
     *
     * @return void
     */
    public static function lock()
    {
        self::$locked = true;
    }

    /**
     * 認証ロックを解除する
     *
     * @return void
     */
    public static function unlock()
    {
        $sessionData = Session::all();
        foreach ($sessionData as $key => $value) {
            if ($key === stristr($key, self::$sessionKey . '_')) {
                Session::forget($key);
            }
        }

        self::$locked = false;
    }

    /**
     * 認証済みかどうかを返す
     *
     * @return bool
     */
    public static function isAuthenticated(): bool
    {
        if (self::isLocked()) {
            return false;
        }

        if (Session::has(self::$sessionKey)) {
            $entity = Session::get(self::$sessionKey);
            if ($entity instanceof Model && null != $entity->id) {
                return true;
            }
        }

        return false;
    }

    /**
     * 認証済みユーザーを返す
     *
     * @return Model|null  認証済みじゃなかった場合はnullを返す
     */
    public static function getAuthenticatedUserEntity()
    {
        if (! self::isAuthenticated()) {
            return null;
        }

        return Session::get(self::$sessionKey)->refresh();
    }

    /**
     * 認証済みユーザーをデータベースから取得し直して再セットする
     *
     * @return bool
     */
    public static function freshAuthenticatedUserEntity(): bool
    {
        $entity = self::getAuthenticatedUserEntity();
        if ($entity instanceof Model) {
            $repository = Application::getInstance()->make(self::$repositoryClass);
            Session::put(self::$sessionKey, $repository->find($entity->id));
            return true;
        }

        return false;
    }

    /**
     * 認証クリア
     *
     * @return self
     */
    public function clear(): self
    {
        $sessionData = Session::all();
        foreach ($sessionData as $key => $value) {
            if ($key === stristr($key, self::$sessionKey)) {
                Session::forget($key);
            }
        }

        self::$locked = false;

        return $this;
    }

    /**
     * 資格情報(ログインIDとパスワード)を返す
     *
     * @return array
     * @throws InvalidArgumentException
     */
    protected function credentials(): array
    {
        if (! $this->request instanceof Request) {
            throw new InvalidArgumentException('This service must have $request parameter of the \Illuminate\Http\Request instance.');
        }
        return $this->request->only(self::$identityAttribute, self::$passwordAttribute);
    }

    /**
     * 認証エラーの監視や認証ロックを使用するかどうかを返す
     *
     * @return bool
     */
    public static function isObservationAvailable(): bool
    {
        if (self::$observateDuring && self::$maxLoginAttempts && self::$lockoutTime && self::$observateDuring) {
            return true;
        }

        return false;
    }
}
