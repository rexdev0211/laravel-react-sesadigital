<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ArtisanSignin extends Model {
    
    protected $fillable = [
        'user_id', 'artisan_id', 'phone','access_type','access_code','access_level','access_level_date','message','slug'
    ];

    protected $hidden = [];

    protected $casts = [];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function artisan() {
        return $this->belongsTo(Artisan::class, 'artisan_id');
    }
}
