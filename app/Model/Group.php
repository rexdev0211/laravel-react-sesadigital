<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Group extends Model {
    use Sluggable,SluggableScopeHelpers;

    protected $fillable = [
        'created_id','estate_id', 'name','user_id','group_type','group_type_value','slug','status'
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
