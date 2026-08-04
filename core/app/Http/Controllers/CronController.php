<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Http\Controllers\Admin\LotteryController;
use App\Lib\CurlRequest;
use App\Models\CronJob;
use App\Models\CronJobLog;
use App\Models\PhaseCreationSchedule;
use App\Models\PickedTicket;
use App\Models\UserMultiDraw;
use App\Models\UserPick;
use Carbon\Carbon;

class CronController extends Controller
{
    public function cron()
    {
        $general            = gs();
        $general->last_cron = now();
        $general->save();

        $crons = CronJob::with('schedule');

        if (request()->alias) {
            $crons->where('alias', request()->alias);
        } else {
            $crons->where('next_run', '<', now())->where('is_running', Status::YES);
        }
        $crons = $crons->get();
        foreach ($crons as $cron) {
            $cronLog              = new CronJobLog();
            $cronLog->cron_job_id = $cron->id;
            $cronLog->start_at    = now();
            if ($cron->is_default) {
                $controller = new $cron->action[0];
                try {
                    $method = $cron->action[1];
                    $controller->$method();
                } catch (\Exception $e) {
                    $cronLog->error = $e->getMessage();
                }
            } else {
                try {
                    CurlRequest::curlContent($cron->url);
                } catch (\Exception $e) {
                    $cronLog->error = $e->getMessage();
                }
            }
            $cron->last_run = now();
            $cron->next_run = now()->addSeconds($cron->schedule->interval);
            $cron->save();

            $cronLog->end_at = $cron->last_run;

            $startTime         = Carbon::parse($cronLog->start_at);
            $endTime           = Carbon::parse($cronLog->end_at);
            $diffInSeconds     = $startTime->diffInSeconds($endTime);
            $cronLog->duration = $diffInSeconds;
            $cronLog->save();
        }
        if (request()->target == 'all') {
            $notify[] = ['success', 'Cron executed successfully'];
            return back()->withNotify($notify);
        }
        if (request()->alias) {
            $notify[] = ['success', keyToTitle(request()->alias) . ' executed successfully'];
            return back()->withNotify($notify);
        }
    }

    public function createPhase()
    {
        try {
            $schedules = PhaseCreationSchedule::where(function ($query) {
                $query->where('day', now()->format('D'))->orWhere('day', now()->format('d'));
            })->whereHas('lottery', function ($lottery) {
                $lottery->active()->whereDoesntHave('phases', function ($phase) {
                    $phase->whereDate('created_at', '>=', now());
                });
            })->get();

            foreach ($schedules as $key => $schedule) {
                LotteryController::createAutoPhase($schedule);
            }
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    public function createMultiDrawUserPick()
    {
        try {

            $userMultiDraws = UserMultiDraw::where('remaining_draw', '>', 0)->whereHas('userPick', function ($userPick) {
                $userPick->paid();
            })->whereHas('lottery', function ($lottery) {
                $lottery->active()->whereHas('phases', function ($phase) {
                    $phase->where('status', Status::ENABLE)->where('is_set_winner', Status::NO);
                });
            })->with(['userPick.pickedTickets', 'lottery', 'lottery.phases' => function ($query) {
                $query->active()->where('is_set_winner', Status::NO);
            }])->get();

            foreach ($userMultiDraws as $key => $userMultiDraw) {
                $nextPhase = $userMultiDraw->lottery->phases->where('draw_date', '>', $userMultiDraw->last_draw_date)->first();

                if (!$nextPhase) {
                    continue;
                }

                $userPick           = new UserPick();
                $userPick->user_id  = $userMultiDraw->user_id;
                $userPick->phase_id = $nextPhase->id;
                $userPick->amount   = $userMultiDraw->userPick->amount;
                $userPick->status   = Status::PAYMENT_SUCCESS;
                $userPick->save();

                $this->pickTickets($userPick, $userMultiDraw->userPick->pickedTickets);

                $userMultiDraw->remaining_draw -= 1;
                $userMultiDraw->advance -= $userPick->amount;
                $userMultiDraw->last_draw_date = $nextPhase->draw_date;
                $userMultiDraw->save();
            }
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    private function pickTickets($userPick, $tickets)
    {
        foreach ($tickets as $key => $ticket) {
            $pickedTicket               = new PickedTicket();
            $pickedTicket->user_pick_id = $userPick->id;
            $pickedTicket->normal_balls = $ticket->normal_balls;
            $pickedTicket->power_balls  = $ticket->power_balls;
            $pickedTicket->save();
        }
    }
}
