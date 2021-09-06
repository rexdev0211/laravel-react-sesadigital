<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Visitor extends Model {
    use Sluggable,SluggableScopeHelpers;

    protected $fillable = [
        'created_id','user_id','estate_id', 'name', 'phone','access_type','access_code','access_level','access_level_date','message','is_signout_required','is_signout','slug','status'
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

    public function resident() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function visitorVisit() {
        return $this->hasOne(VisitorVisit::class, 'visitor_id');
    }
}
