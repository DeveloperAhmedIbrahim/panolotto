<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhaseCreationSchedule extends Model
{
    
    public function lottery()
    {
        return $this->belongsTo(Lottery::class);
    }
}
