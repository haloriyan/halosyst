<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Agent extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'topic_id','name','email','password','photo','is_busy'
    ];

    public function topic() {
        return $this->belongsTo('App\Models\Topic', 'topic_id');
    }
}
