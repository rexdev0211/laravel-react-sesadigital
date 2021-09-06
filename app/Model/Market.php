<?php

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    protected $fillable = [
        'market_list_name', 'publish_date','end_date','notes','assigned_stated','status','market_charges'
    ];
}
