<?php
declare(strict_types=1);

namespace App\Models\Entities;

use DomainException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DesiredTime extends Model
{
    use SoftDeletes;

    const RANGE_OF_TIMES = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24];

    protected $table = 'desired_times';
    protected $fillable = [
        'from',
        'to',
    ];
    protected $casts = [
        'from'          => 'integer',
        'to'            => 'integer',
    ];
    protected $dates = [
        'deleted_at',
    ];
    protected $appends = [
        'period',
    ];

    public function getPeriodAttribute()
    {
        return $this->from . '時～' . $this->to . '時';
    }
}
