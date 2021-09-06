<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model {

    protected $fillable = [
        'created_id','key', 'key_value','setting_type','status'
    ];

    protected $hidden = [];

    protected $casts = [];

}
