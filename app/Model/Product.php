<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Product extends Model {
    use Sluggable,SluggableScopeHelpers;

    protected $fillable = [
        'created_id','estate_id','name','photo','amount_type','amount','total_amount', 'amount_pay_type','installment_type','description', 'slug', 'status'
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

    public function estate() {
        return $this->belongsTo(Estate::class, 'estate_id');
    }

    public function installments() {
        return $this->hasMany(ProductInstallment::class, 'product_id');
    }
}
