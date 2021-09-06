<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Artisan extends Model {
    use Sluggable,SluggableScopeHelpers;

    protected $fillable = [
        'created_id', 'name','email', 'business_name','phone','address','bvn','nin','write_up','photo','account_name','account_number','bank_name','notes','slug','status'
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

    public function artisanLinkedCategory() {
        return $this->hasMany(ArtisanLinkedCategory::class, 'artisan_id');
    }

    public function artisanRating() {
        return $this->hasMany(ArtisanRating::class, 'artisan_id');
    }
}
