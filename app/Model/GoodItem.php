<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class GoodItem extends Model {
    use Sluggable,SluggableScopeHelpers;

    protected $fillable = [
        'good_id','name','number_of_item','image','amount','slug'
    ];

    protected $hidden = [];

    protected $casts = [];

    public function sluggable() {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function goodBuyItems() {
        return $this->hasMany(GoodBuyItem::class, 'good_item_id');
    }
}
