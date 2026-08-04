<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Winner extends Model
{
    protected $casts = [
        'normal_balls' => 'array',
        'power_balls' => 'array'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function phase(){
        return $this->belongsTo(Phase::class);
    }

    public function pickedTicket(){
        return $this->belongsTo(PickedTicket::class);
    }
}
