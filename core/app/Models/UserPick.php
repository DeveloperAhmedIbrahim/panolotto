<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;

class UserPick extends Model
{
    public function phase()
    {
        return $this->belongsTo(Phase::class);
    }

    public function pickedTickets()
    {
        return $this->hasMany(PickedTicket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userMultiDraw()
    {
        return $this->hasOne(UserMultiDraw::class);
    }

    public function scopePaid($query)
    {
        $query->where('status', Status::PAYMENT_SUCCESS);
    }

    public function scopeUnpaid($query)
    {
        $query->where('status', Status::PAYMENT_INITIATE);
    }

    public function scopePending($query)
    {
        $query->where('status', Status::PAYMENT_PENDING);
    }

    public function scopeRejected($query)
    {
        $query->where('status', Status::PAYMENT_REJECT);
    }

    public function lotteryPrice()
    {
        return $this->amount / $this->pickedTickets->count();
    }
}
