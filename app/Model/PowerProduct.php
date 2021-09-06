<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class PowerProduct extends Model {
    use Sluggable,SluggableScopeHelpers;

    protected $fillable = [
        'created_id','estate_id','user_id','power_company','amount','power_unit', 'power_code', 'slug', 'status','purchased_at'
    ];

    protected $hidden = [];

    protected $casts = [];

    public function sluggable() {
        return [
            'slug' => [
                'source' => 'power_company'
            ]
        ];
    }

    public function estate() {
        return $this->belongsTo(Estate::class, 'estate_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
