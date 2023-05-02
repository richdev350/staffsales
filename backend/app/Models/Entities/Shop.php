<?php
declare(strict_types=1);

namespace App\Models\Entities;

use DomainException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    use SoftDeletes;

    protected $table = 'shops';
    protected $fillable = [
        'code',
        'name',
        'zip_code',
        'prefecture_id',
        'city',
        'address',
        'tel',
    ];
    protected $casts = [
        'code'          => 'string',
        'name'          => 'string',
        'zip_code'      => 'string',
        'prefecture_id' => 'integer',
        'city'          => 'string',
        'address'       => 'string',
        'tel'           => 'string',
    ];
    protected $dates = [
        'deleted_at',
    ];

    public function getManagerIdAttribute(): int
    {
        if ($this->admin_users()->role('manager')->get(['id'])->isEmpty()) {
            return -1;
        } else {
            return $this->admin_users()->role('manager')->first()->id;
        }
    }

    public function getStaffIdAttribute(): int
    {
        if ($this->admin_users()->role('shop')->get(['id'])->isEmpty()) {
            return -1;
        } else {
            return $this->admin_users()->role('shop')->first()->id;
        }
    }

    public function prefecture()
    {
        return $this->belongsTo('App\Models\Entities\Prefecture');
    }

    public function admin_users()
    {
        return $this->belongsToMany('App\Models\Entities\AdminUser', 'admin_users_shops', 'shop_id', 'admin_user_id')
        ->using('App\Models\Entities\AdminUsersShops')
        ->withTimestamps()
        ->withPivot([
            'created_at',
            'updated_at'
        ]);
    }

    public function items()
    {
        return $this->belongsToMany('App\Models\Entities\Item', 'items_shops_stocks', 'shop_id', 'item_id',)
        ->using('App\Models\Entities\ItemsShopsStocks')
        ->withTimestamps()
        ->withPivot([
            'quantity',
            'created_at',
            'updated_at'
        ]);
    }

    public function ignore_items()
    {
        return $this->belongsToMany('App\Models\Entities\Item', 'shops_ignore_items', 'shop_id', 'item_id')
        ->using('App\Models\Entities\ShopsIgnoreItems')
        ->withTimestamps()
        ->withPivot([
            'created_at',
            'updated_at'
        ]);
    }
}
