<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Advert extends Model {
    use Sluggable,SluggableScopeHelpers;

    protected $fillable = [
        'created_id','estate_id', 'name','start_date','end_date','photo','external_url','slug','status'
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

    public function estate() {
        return $this->belongsTo(Estate::class, 'estate_id');
    }
}
