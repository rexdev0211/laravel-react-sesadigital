<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Good extends Model {
    use Sluggable,SluggableScopeHelpers;

    protected $fillable = [
        'created_id','type','item_type','name','number_of_share','slug','status'
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

    public function goodItems() {
        return $this->hasMany(GoodItem::class, 'good_id');
    }
}
