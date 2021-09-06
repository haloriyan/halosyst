<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id','sender','body','attachment'
    ];

    public function room() {
        return $this->belongsTo('App\Models\Room', 'room_id');
    }
    public function agent() {
        return $this->belongsTo('App\Models\Agent', 'agent_id');
    }
    public function visitor() {
        return $this->belongsTo('App\Models\Visitor', 'visitor_id');
    }
}
