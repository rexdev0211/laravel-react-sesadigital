<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model {
    protected $fillable = [
        'estate_id','template_id','message_id', 'to_id','to_name','phone','request_data','response_data','status'
    ];

    protected $hidden = [];

    protected $casts = [];
}
