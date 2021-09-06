<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class PaymentMethod extends Model {

    use Sluggable,SluggableScopeHelpers;

    protected $fillable = [
        'created_id','name','payment_mode','api_key','api_secret_key','contract_code','payment_url','sandbox_api_key','sandbox_api_secret_key','sandbox_contract_code','sandbox_payment_url','slug','status'
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

}
