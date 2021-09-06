<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id','agent_id','visitor_id','ended_at'
    ];

    public function visitor() {
        return $this->belongsTo('App\Models\Visitor', 'visitor_id');
    }
    public function topic() {
        return $this->belongsTo('App\Models\Topic', 'topic_id');
    }
    public function agent() {
        return $this->belongsTo('App\Models\Agent', 'agent_id');
    }
    public function chats() {
        return $this->hasMany('App\Models\Chat', 'room_id');
    }
}
