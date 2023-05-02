<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $table = 'orders';
    protected $fillable = [
        'name',
        'sum',
        'secure_code',
        'staff_id',
    ];

    protected $casts = [
        'name'            => 'string',
        'sum'             => 'integer',
        'secure_code'     => 'string',
        'staff_id'        => 'string',
    ];

    public function order_details()
    {
        return $this->hasMany('App\Models\Entities\OrderDetail');
    }

}
