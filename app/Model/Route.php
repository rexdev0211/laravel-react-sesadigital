<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Route extends Model {

    protected $fillable = [
        'parent_id', 'route_key', 'name', 'is_display', 'display_order', 'is_admin', 'is_estate_manager','is_company', 'is_role','is_guard','is_resident'
    ];

    protected $hidden = [];

    protected $casts = [];
}
