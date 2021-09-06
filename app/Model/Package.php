<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Package extends Model {
    use Sluggable,SluggableScopeHelpers;

    protected $fillable = [
        'created_id','name','package_type', 'price','estate_service_charge','commission_fee','total_price','show_total_price','can_add_user','slug','status'
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
}
