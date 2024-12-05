<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMultiDraw extends Model
{
    public function userPick()
    {
        return $this->belongsTo(UserPick::class);
    }

    public function lottery()
    {
        return $this->belongsTo(Lottery::class);
    }
}
