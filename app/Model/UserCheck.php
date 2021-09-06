<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserCheck extends Model {
    
    protected $fillable = [
        'estate_id', 'user_id', 'tag_number','check_in_by_id','check_in_at','check_out_by_id','check_out_at'
    ];

    protected $hidden = [];

    protected $casts = [];

    public function estate() {
        return $this->belongsTo(Estate::class, 'estate_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function checkInBy() {
        return $this->belongsTo(User::class, 'check_in_by_id');
    }
    
    public function checkOutBy() {
        return $this->belongsTo(User::class, 'check_out_by_id');
    }
}
