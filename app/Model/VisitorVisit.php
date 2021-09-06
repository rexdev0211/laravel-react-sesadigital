<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VisitorVisit extends Model {

    protected $fillable = [
        'visit_type','estate_id','user_id','visitor_id','tag_number', 'name', 'phone','photo','message','visit_in_by_id','visit_in_at','visit_out_by_id','visit_out_at','decline_reason','status'
    ];

    protected $hidden = [];

    protected $casts = [];

    public function estate() {
        return $this->belongsTo(Estate::class, 'estate_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function visitor() {
        return $this->belongsTo(Visitor::class, 'visitor_id');
    }

    public function visitInBy() {
        return $this->belongsTo(User::class, 'visit_in_by_id');
    }
    
    public function visitOutBy() {
        return $this->belongsTo(User::class, 'visit_out_by_id');
    }
}
