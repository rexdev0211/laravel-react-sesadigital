<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class SendMeBuyItem extends Model {

    use Sluggable,SluggableScopeHelpers;

    protected $fillable = [
        'estate_id','send_me_id','send_me_item_id','user_id','send_me_name','send_me_item_name','quantity_type','item_price','quantity','total_amount','slug'
    ];

    protected $hidden = [];

    protected $casts = [];

    public function sluggable() {
        return [
            'slug' => [
                'source' => 'send_me_item_name'
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
