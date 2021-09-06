<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Estate extends Model {
    use Sluggable,SluggableScopeHelpers;

    protected $fillable = [
        'created_id','manager_id','company_id', 'name','wallet_balance', 'phone','address','near_by_location','photo','description','num_of_users','is_signout_required','slug','status'
    ];

    protected $hidden = [];

    protected $casts = [];

    public function sluggable() {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function estateManager() {
        return $this->belongsTo(User::class, 'manager_id');
    }
    public function securityCompany() {
        return $this->belongsTo(User::class, 'company_id');
    }
    public function gaurds() {
        return $this->hasMany(User::class, 'estate_id');
    }
}
