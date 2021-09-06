<?php

namespace App\Model;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Laravel\Passport\HasApiTokens;


class User extends Authenticatable implements JWTSubject {
    use HasApiTokens, Notifiable, Sluggable,SluggableScopeHelpers;

    protected $fillable = [
        'created_id','user_type','estate_id','resident_id','company_id','manager_id','username','resident_code','first_name','last_name', 'email', 'password','phone','photo','dob','house_code', 'address','gender','resident_category','resident_status','resident_type','add_no_of_user','route_id','role_id','has_password_updated','wallet_balance','device_token','slug','status'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier() {
        return $this->getKey();
    }
    public function getJWTCustomClaims() {
        return [];
    }

    public function sluggable() {
        return [
            'slug' => [
                'source' => 'first_name'
            ]
        ];
    }

    public function estate() {
        return $this->belongsTo(Estate::class, 'estate_id');
    }

    public function securityCompany() {
        return $this->belongsTo(User::class, 'company_id');
    }

    public function role() {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function managerEstates() {
        return $this->hasMany(Estate::class, 'manager_id');
    }

    public function companyEstates() {
        return $this->hasMany(Estate::class, 'company_id');
    }
}
