<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ArtisanLinkedCategory extends Model {

    protected $fillable = [
        'created_id','artisan_id', 'artisan_category_id'
    ];

    protected $hidden = [];

    protected $casts = [];

    public function artisanCategory() {
        return $this->belongsTo(ArtisanCategory::class, 'artisan_category_id');
    }
}
