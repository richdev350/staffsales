<?php

namespace App\Models\Entities;

use DomainException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class Prefecture extends Model
{
    protected $table = 'prefectures';
    protected $fillable = [
        'region_id',
        'code',
        'name',
    ];
    protected $casts = [
        'region_id' => 'integer',
        'code'      => 'string',
        'name'      => 'string',
    ];
    protected $dates = [
        'deleted_at',
    ];

    public function region(){
        return $this->belongsTo('App\Models\Entities\Region');
    }
}
