<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VisitorSetting extends Model {

    protected $fillable = [
        'created_id','user_id', 'check_in', 'check_out','is_user_select','user_id_check_in','user_id_check_out','authenticate_walkin'
    ];

    protected $hidden = [];

    protected $casts = [];

    public function resident() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
