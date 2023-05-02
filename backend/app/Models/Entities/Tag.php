<?php

namespace App\Models\Entities;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;

    const DELIMITER = ' ';

    protected $table = 'tags';

    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'tags' => 'string',
    ];

    protected $dates = [
        'deleted_at',
    ];

    public function items()
    {
        return $this->belongsToMany('App\Models\Entities\Item', 'items_tags', 'tag_id', 'item_id')
        ->using('App\Models\Entities\ItemsTags')
        ->withTimestamps()
        ->withPivot([
            'created_at',
            'updated_at'
        ]);
    }

}
