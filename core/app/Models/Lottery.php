<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Lottery extends Model
{
    use GlobalStatus;

    protected $casts = [
        'line_variations' => 'array',
        'days' => 'object'
    ];

    public function phases()
    {
        return $this->hasMany(Phase::class);
    }
    public function activePhase()
    {
        return $this->hasOne(Phase::class)->active()->where('draw_date', '>=', now()->toDateTimeString());
    }

    public function winningSettings()
    {
        return $this->hasMany(WinningSetting::class);
    }

    public function phaseCreationSchedules()
    {
        return $this->hasMany(PhaseCreationSchedule::class);
    }

    public function multiDrawOptions()
    {
        return $this->hasMany(MultiDrawOption::class);
    }

    public function scopeManual($query)
    {
        $query->where('auto_creation_phase', Status::DISABLE);
    }

    public function maxPrize()
    {
        return $this->winningSettings->max('prize_money');
    }

    public function winningCombinations()
    {
        $combination = [];
        for ($p = $this->total_picking_power_ball; $p >= 0; $p--) {
            for ($n = $this->total_picking_ball; $n >= 0; $n--) {
                $combination[] = [
                    'power_ball' => $p,
                    'normal_ball' => $n
                ];
            }
        }

        array_pop($combination);
        return $combination;
    }
}
