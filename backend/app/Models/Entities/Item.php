<?php

namespace App\Models\Entities;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    const MIN_AMOUNT = 1;

    protected $table = 'items';
    protected $fillable = [
        'maker_id',
        'jan',
        'name',
        'abridge',
        'summary',
        'description_title',
        'description',
        'labels',
        'notes',
        'self_medication',
        'price',
        'is_stock',
        'is_visible',
        'spec',
        'max_amount',
        'sort_no',
    ];
    protected $casts = [
        'maker_id'              => 'integer',
        'jan'                   => 'string',
        'name'                  => 'string',
        'abridge'               => 'string',
        'summary'               => 'string',
        'description_title'     => 'string',
        'description'           => 'string',
        'labels'                => 'json',
        'notes'                 => 'string',
        'self_medication'       => 'integer',
        'price'                 => 'integer',
        'is_stock'              => 'boolean',
        'is_visible'            => 'boolean',
        'note'                  => 'string',
        'spec'                  => 'json',
        'max_amount'            => 'integer',
        'sort_no'               => 'integer',
    ];
    protected $dates = [
        'deleted_at',
    ];

    public function files()
    {
        return $this->belongsToMany('App\Models\Entities\File', 'items_files', 'item_id', 'file_id')
        ->using('App\Models\Entities\ItemsFiles')
        ->withTimestamps()
        ->withPivot([
            'sort_no',
            'created_at',
            'updated_at'
        ])->orderBy('sort_no');
    }

    public function item_categories()
    {
        return $this->belongsToMany('App\Models\Entities\ItemCategory', 'item_categories_items', 'item_id', 'item_category_id')
        ->using('App\Models\Entities\ItemCategoriesItems')
        ->withTimestamps()
        ->withPivot([
            'created_at',
            'updated_at'
        ]);
    }

    public function maker()
    {
        return $this->belongsTo('App\Models\Entities\Maker');
    }

    public function shops()
    {
        return $this->belongsToMany('App\Models\Entities\Item', 'items_shops_stocks', 'item_id', 'shop_id')
        ->using('App\Models\Entities\ItemsShopsStocks')
        ->withTimestamps()
        ->withPivot([
            'quantity',
            'created_at',
            'updated_at'
        ]);
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\Entities\Tag', 'items_tags', 'item_id', 'tag_id')
        ->using('App\Models\Entities\ItemsTags')
        ->withTimestamps()
        ->withPivot([
            'created_at',
            'updated_at'
        ]);
    }

    public function getTagsTextAttribute(){
        return implode(Tag::DELIMITER, $this->tags->pluck('name')->toArray());
    }
}
