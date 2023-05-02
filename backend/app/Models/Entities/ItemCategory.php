<?php

namespace App\Models\Entities;

use DomainException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Franzose\ClosureTable\Models\Entity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Franzose\ClosureTable\Contracts\EntityInterface;

class ItemCategory extends Entity implements EntityInterface
{
    use SoftDeletes;

    protected $table = 'item_categories';
    protected $closure = 'App\Models\Entities\ItemCategoryClosure';

    protected $fillable = [
        'name',
    ];
    protected $casts = [
        'name'          => 'string',
    ];
    protected $dates = [
        'deleted_at',
    ];
}
