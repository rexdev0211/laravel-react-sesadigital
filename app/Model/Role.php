<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Role extends Model {
    use Sluggable,SluggableScopeHelpers;

    protected $fillable = [
        'created_id','user_id','is_system','roleType', 'name','slug','status'
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

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function roleRoute() {
        return $this->hasOne(RoleRoute::class, 'role_id');
    }
}
