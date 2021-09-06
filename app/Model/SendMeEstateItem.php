<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SendMeEstateItem extends Model {

    protected $fillable = [
        'estate_id','send_me_id','send_me_item_id','available_on'
    ];

    protected $hidden = [];

    protected $casts = [];
    
    public function estate() {
        return $this->belongsTo(Estate::class, 'estate_id');
    }

    public function send_me() {
        return $this->belongsTo(SendMe::class, 'send_me_id');
    }
}
