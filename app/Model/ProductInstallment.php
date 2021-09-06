<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductInstallment extends Model {

    protected $fillable = [
        'product_id','amount'
    ];

    protected $hidden = [];

    protected $casts = [];

}
