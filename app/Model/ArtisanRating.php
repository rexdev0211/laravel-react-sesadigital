<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ArtisanRating extends Model {
    
    protected $fillable = [
        'user_id', 'artisan_id', 'rating','comment','slug'
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
