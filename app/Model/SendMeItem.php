<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class SendMeItem extends Model {
    use Sluggable,SluggableScopeHelpers;

    protected $fillable = [
        'send_me_id','name','quantity_type','item_price','slug','status'
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

    public function send_me() {
        return $this->belongsTo(SendMe::class, 'send_me_id');
    }
}
