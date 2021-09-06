<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OauthAccessTokens extends Model {

    protected $fillable = [
        'user_id', 'client_id', 'name', 'scopes','revoked','expires_at'
    ];

    protected $hidden = [];

    protected $casts = [];
}
