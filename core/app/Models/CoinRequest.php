<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class CoinRequest extends Model
{
    public function user()
    {

        return $this->belongsTo(User::class, 'user_id');
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(
            get: fn () => $this->badgeData(),
        );
    }

    public function badgeData()
    {
        $html = '';
        if ($this->status == Status::ENABLE) {
            $html = '<span class="badge badge--success">' . trans('Approved') . '</span>';
        } else {
            $html = '<span><span class="badge badge--warning">' . trans('Pending') . '</span></span>';
        }
        return $html;
    }
}
