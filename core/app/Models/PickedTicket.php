<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickedTicket extends Model
{
    protected $casts = [
        'normal_balls' => 'array',
        'power_balls' => 'array',
    ];

    public function userPick(){
        return $this->belongsTo(UserPick::class);
    }
}
