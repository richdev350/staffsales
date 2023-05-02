<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Publish extends Model
{
    protected $table = 'system_mode_settings';
    protected $fillable = [
        'id',
        'name',
        'exhibit_date',
        'sales_start_date',
        'end_of_sale_date',
        'is_end_of_sale_date_visible',
        'emergency_flag',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id'                   => 'bigIncrements',
        'name'                 => 'string',
    ];

    protected $dates = [
        'exhibit_date',
        'sales_start_date',
        'end_of_sale_date',
        'created_at',
        'updated_at',
    ];
}
