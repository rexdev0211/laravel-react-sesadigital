<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RoleRoute extends Model {

    protected $fillable = [
        'role_id', 'route_id'
    ];

    protected $hidden = [];

    protected $casts = [];
}
