<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','contact','token'
    ];

    public function room() {
        return $this->hasOne('App\Models\Room', 'visitor_id');
    }
}
