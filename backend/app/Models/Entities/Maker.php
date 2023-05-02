<?php
declare(strict_types=1);

namespace App\Models\Entities;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Maker extends Model
{
    use SoftDeletes;
    protected $table = 'makers';
    protected $fillable = [
        'name'
    ];
    protected $casts = [
        'name' => 'string'
    ];
    protected $dates = [
        'deleted_at'
    ];

    public function visible_items()
    {
        return $this->hasMany('App\Models\Entities\Item')->where('is_visible', true);
    }
}
