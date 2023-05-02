<?php
declare(strict_types=1);

namespace App\Models\Entities;

use DomainException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;

class AdminUser extends Authenticatable
{
    use SoftDeletes;
    use HasRoles;
    use HasPermissions;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_users';

    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'login_id',
        'email',
        'password',
        'token',
        'token_expired_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'name'             => 'string',
        'login_id'         => 'string',
        'email'            => 'string',
        'password'         => 'string',
        'token'            => 'string',
        'token_expired_at' => 'datetime',
    ];

    /**
    * 日付へキャストする属性
     *
     * @var array
     */
    protected $dates = [
        'token_expired_at',
        'deleted_at',
    ];

    /**
     * 自身が持つ店舗（複数）のリレーションを返す
     */
    public function shops()
    {
        return $this->belongsToMany('App\Models\Entities\Shop', 'admin_users_shops', 'admin_user_id', 'shop_id')
        ->using('App\Models\Entities\AdminUsersShops')
        ->withTimestamps()
        ->withPivot([
            'created_at',
            'updated_at'
        ]);
    }

    /**
     * メールアドレスをセットするミューテータ
     *
     */
    public function setEmailAttribute($value = null)
    {
        $validator = Validator::make(
            ['value' => $value],
            ['value' => [
                'nullable',
                'string',
                'email',
            ]]
        );
        if ($validator->fails()) {
            throw new DomainException(sprintf('Invalid email: %s', $value));
        }

        $this->attributes['email'] = $value;
    }

    /**
     * パスワードをセットするミューテータ
     *
     */
    public function setPasswordAttribute($value = null)
    {
        $validator = Validator::make(
            ['value' => $value],
            ['value' => [
                'nullable',
                'string',
                'regex:/^[0-9a-zA-Z_\-]{8,}$/',
            ]]
        );
        if ($validator->passes()) {
            $this->attributes['password'] = bcrypt($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

}
