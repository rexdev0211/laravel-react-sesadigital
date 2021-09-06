<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Event extends Model {
    use Sluggable,SluggableScopeHelpers;

    protected $fillable = [
        'created_id','user_id', 'event_type', 'name','start_date','start_time','end_date','end_time','access_code','slug','status'
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
        return $this->belongsTo(User::class, 'resident_id');
    }
}
