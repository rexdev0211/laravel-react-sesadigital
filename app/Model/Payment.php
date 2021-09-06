<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model {

    protected $fillable = [
        'created_id','user_id','pay_type','pay_gateway','estate_id','transaction_id','amount','payment_reference','description','package_id','package_name','package_price','package_estate_service_charge','package_commission_fee','package_total_price','package_can_add_user','package_valid_till','purchased_product_id','power_product_id','slug','status'
    ];

    protected $hidden = [];

    protected $casts = [];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function estate() {
        return $this->belongsTo(Estate::class, 'estate_id');
    }

    public function purchasedProduct() {
        return $this->belongsTo(PurchasedProduct::class, 'purchased_product_id');
    }

    public function powerProduct() {
        return $this->belongsTo(powerProduct::class, 'power_product_id');
    }
}
