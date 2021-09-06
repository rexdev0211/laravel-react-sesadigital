<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class NextOfKin extends Model {
    use Sluggable,SluggableScopeHelpers;

    protected $table = "next_of_kins";

    protected $fillable = [
        'created_id','user_id','relationship_id','first_name','last_name','email','phone', 'photo','dob','house_code','address','gender','marital_status','assign_panic_alert','slug'
    ];

    protected $hidden = [];

    protected $casts = [];

    public function sluggable() {
        return [
            'slug' => [
                'source' => 'first_name'
            ]
        ];
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function relationship() {
        return $this->belongsTo(Relationship::class, 'relationship_id');
    }
}
