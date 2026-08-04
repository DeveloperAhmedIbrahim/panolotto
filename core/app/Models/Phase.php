<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Phase extends Model
{
    use GlobalStatus;

    protected $casts = [
        'winning_normal_balls' => 'array',
        'winning_power_balls' => 'array'
    ];

    public function scopeCompleted($query)
    {
        $query->where('is_set_winner', Status::YES);
    }

    public function scopeWinnerNotSet($query)
    {
        $query->where('is_set_winner', Status::NO);
    }

    public function lottery()
    {
        return $this->belongsTo(Lottery::class);
    }

    public function winners()
    {
        return $this->hasMany(Winner::class);
    }

    public function userPicks()
    {
        return $this->hasMany(UserPick::class)->where('status', Status::PAYMENT_SUCCESS);
    }

    public function pickedTickets()
    {
        return $this->hasManyThrough(PickedTicket::class, UserPick::class)->where('user_picks.status', Status::PAYMENT_SUCCESS);
    }

    public function winnerStatusBadge(): Attribute
    {
        return new Attribute(
            function () {
                $html = '';
                if ($this->is_set_winner == Status::YES) {
                    $html = '<span class="badge badge--success">' . trans('Yes') . '</span>';
                } else {
                    $html = '<span class="badge badge--danger">' . trans('No') . '</span>';
                }
                return $html;
            }
        );
    }
}
