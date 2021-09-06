<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GoodBuyItem extends Model {

    protected $fillable = [
        'estate_id','user_id','good_id','good_item_id','number_of_item','amount','total_amount','status'
    ];

    protected $hidden = [];

    protected $casts = [];

    public function estate() {
        return $this->belongsTo(Estate::class, 'estate_id');
    }
    
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function good() {
        return $this->belongsTo(Good::class, 'good_id');
    }
    
    public function goodItem() {
        return $this->belongsTo(GoodItem::class, 'good_item_id');
    }
}
