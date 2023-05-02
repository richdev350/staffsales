<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = 'regions';
    protected $fillable = [
        'name',
    ];
    protected $casts = [
        'name'       => 'string',
    ];
}
