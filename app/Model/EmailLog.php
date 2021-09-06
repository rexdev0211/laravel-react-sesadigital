<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model {
    protected $fillable = [
        'estate_id','message_id','to_id', 'to_name','email','status'
    ];

    protected $hidden = [];

    protected $casts = [];
}
