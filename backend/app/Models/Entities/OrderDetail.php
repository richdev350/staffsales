<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model
{
    use SoftDeletes;

    protected $table = 'order_details';
    protected $fillable = [
        'order_id',
        'item_id',
        'price',
        'amount',
    ];
    protected $casts = [
        'order_id' => 'integer',
        'item_id'  => 'integer',
        'price'    => 'integer',
        'amount'   => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo('App\Models\Entities\Order');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Entities\Item');
    }
}
