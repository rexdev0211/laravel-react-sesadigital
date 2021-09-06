<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PurchasedProduct extends Model {

    protected $fillable = [
        'user_id','estate_id','name','photo','amountType','amount','totalAmount', 'amountPayType','installmentType','paidAmount','producInstallment','paidInstallment','description', 'slug', 'paidStatus'
    ];

    protected $hidden = [];

    protected $casts = [];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function estate() {
        return $this->belongsTo(Estate::class, 'estate_id');
    }
}
