<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Phase;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Winner;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DrawController extends Controller
{
    public function pendingDraw()
    {
        $pageTitle = 'Pending Draws';
        $phases    = Phase::active()->winnerNotSet()->whereDate('draw_date', '<=', now())->with('lottery:id,name')->orderBy('draw_date', 'desc')->get();
        return view('admin.lottery.draw.pending', compact('pageTitle', 'phases'));
    }

    public function selectBalls($id)
    {
        $phase = Phase::active()->winnerNotSet()->whereDate('draw_date', '<=', now())->with('lottery')->findOrFail($id);
        $pageTitle = 'Draw ' . showPhase($phase->phase_no) . ' of ' . $phase->lottery->name;
        return view('admin.lottery.draw.select_balls', compact('pageTitle', 'phase'));
    }

    public function preview(Request $request, $id)
    {
        $phase = Phase::active()->winnerNotSet()->whereDate('draw_date', '<=', now())->with('lottery.winningSettings', 'pickedTickets.userPick.user')->withSum('userPicks as sold_amount', 'amount')->findOrFail($id);
        $this->validateWinningBalls($request, $phase->lottery);

        $winningSettings = $phase->lottery->winningSettings;
        $winningNormalBalls = $request->winning_normal_ball;
        $winningPowerBalls  = $request->winning_power_ball ?? [];
        $winners = [];
        $i = 0;

        foreach ($phase->pickedTickets as $pickedTicket) {
            $nbExists = array_values(array_intersect($winningNormalBalls, $pickedTicket->normal_balls));
            $pwExists = array_values(array_intersect($winningPowerBalls, $pickedTicket->power_balls));
            sort($nbExists);
            sort($pwExists);

            $totalNBExists = count($nbExists);
            $totalPWBExists = count($pwExists);

            if ($totalNBExists ||  $totalPWBExists) {
                $userPick = $pickedTicket->userPick;
                $user = $userPick->user;

                $winningSetting = @$phase->lottery->winningSettings->where('normal_ball', $totalNBExists)->where('power_ball', $totalPWBExists)->first();

                if (!$winningSetting || $winningSetting->status === 0) {
                    continue;
                }

                $winners[$i]['name']                 =  $user->fullname;
                $winners[$i]['normal_balls']         = $pickedTicket->normal_balls;
                $winners[$i]['power_balls']          = $pickedTicket->power_balls;
                $winners[$i]['winning_power_balls']  =  $pwExists;
                $winners[$i]['winning_normal_balls'] =  $nbExists;
                $winners[$i]['prize_money']          =  $winningSetting->prize_money ?? 0;
                $i++;
            }
        }

        $winningAmount = count($winners) > 0 ? array_sum(array_column($winners, 'prize_money')) : 0;
        $pageTitle     = 'Preview of ' . $phase->lottery->name . ' ' . showPhase($phase->phase_no) . ' draw';
        return view('admin.lottery.draw.preview', compact('pageTitle', 'phase', 'winners', 'winningAmount', 'winningNormalBalls', 'winningPowerBalls'));
    }

    public function submit(Request $request, $id)
    {
        $request->validate([
            'winning_normal_ball' => 'required|array',
            'winning_power_ball'  => 'nullable|array',
        ]);

        $phase = Phase::active()->winnerNotSet()->whereDate('draw_date', '<=', now())->with('lottery.winningSettings', 'pickedTickets.userPick.user')->findOrFail($id);

        if (@$phase->lottery->has_power_ball && !$request->winning_power_ball) {
            $notify[] = ['error', 'Winning power balls are required'];
            return to_route('admin.draw.preview', $phase->id)->withNotify($notify);
        }

        if (!$phase) {
            $notify[] = ['error', 'Invalid lottery phase selected'];
            return to_route('admin.draw.preview', $phase->id)->withNotify($notify);
        }

        if (!$phase->lottery->winningSettings->count()) {
            $notify[] = ['error', 'Set winning setting of this lottery first'];
            return to_route('admin.lottery.winning.setting', $phase->lottery_id)->withNotify($notify);
        }

        $this->validateWinningBalls($request, $phase->lottery);


        $winningNormalBalls = $request->winning_normal_ball;
        $winningPowerBalls  = $request->winning_power_ball ?? [];

        sort($winningNormalBalls);
        sort($winningPowerBalls);

        $phase->winning_normal_balls =  array_map('intVal', $winningNormalBalls);
        $phase->winning_power_balls  = array_map('intVal', $winningPowerBalls);
        $phase->save();

        $this->setWinners($phase);

        $this->createTransaction($phase);

        $phase->is_set_winner = Status::YES;
        $phase->draw_at = now();
        $phase->save();

        $notify[] = ['success', 'Winner set successfully'];
        return to_route('admin.draw.pending')->withNotify($notify);
    }

    private function setWinners($phase)
    {
        try {
            $winningSettings = $phase->lottery->winningSettings;

            foreach ($phase->pickedTickets as $pickedTicket) {
                $nbExists = array_values(array_intersect($phase->winning_normal_balls, $pickedTicket->normal_balls));
                $pwExists = array_values(array_intersect($phase->winning_power_balls, $pickedTicket->power_balls));
                sort($nbExists);
                sort($pwExists);

                $totalNBExists = count($nbExists);
                $totalPWBExists = count($pwExists);

                if ($totalNBExists ||  $totalPWBExists) {
                    $userPick = $pickedTicket->userPick;
                    $user     = $userPick->user;

                    $winningSetting = $winningSettings->where('normal_ball', $totalNBExists)->where('power_ball', $totalPWBExists)->first();

                    if($winningSetting->status === 1)
                    {
                        $winner                   = new Winner();
                        $winner->user_id          =  $user->id;
                        $winner->phase_id         =  $phase->id;
                        $winner->picked_ticket_id = $pickedTicket->id;
                        $winner->normal_balls     =  $nbExists;
                        $winner->power_balls      =  $pwExists;
                        $winner->combination      =  $totalPWBExists . 'x' . $totalNBExists;
                        $winner->prize_money      =  $winningSetting->prize_money;
                        $winner->save();

                        $user->balance += $winner->prize_money;
                        $user->save();
                    }
                }
            }
        } catch (\Exception $e) {
            throw ValidationException::withMessages(['error' => $e->getMessage()]);
        }
    }

    private function createTransaction($phase)
    {
        $winners = Winner::where('phase_id', $phase->id)->selectRaw("SUM(prize_money) as prize_money, user_id")->groupBy('user_id')->get();
        foreach ($winners as $winner) {
            $user                      = User::find($winner->user_id);
            $transaction               = new Transaction();
            $transaction->user_id      = $user->id;
            $transaction->amount       = $winner->prize_money;
            $transaction->charge       = 0;
            $transaction->post_balance = $user->balance;
            $transaction->trx_type     = '+';
            $transaction->trx          = getTrx();
            $transaction->details      = 'Prize money for winning the lottery';
            $transaction->remark       = 'prize_money';
            $transaction->save();

            $wonTickets = Winner::where('phase_id', $phase->id)->where('user_id', $user->id)->count();
            notify($user, 'WINNER', [
                'lottery'              => $phase->lottery->name,
                'trx'                  => $transaction->trx,
                'won_tickets'          => $wonTickets,
                'winning_normal_balls' => implode(',', $phase->winning_normal_balls),
                'winning_power_balls'  => implode(',', $phase->winning_power_balls),
                'prize'                => showAmount($winner->prize_money, currencyFormat: false),
                'post_balance'         => showAmount($user->balance, currencyFormat: false)
            ]);

            if (gs('lottery_win_commission')) {
                levelCommission($user, $winner->prize_money, 'lottery_win_commission', $transaction->trx);
            }
        }
    }

    private function validateWinningBalls($request, $lottery)
    {
        $allInRange = true;
        if ($lottery->ball_start_from) {
            $maximumNormalBallNumber = $lottery->no_of_ball;
        } else {
            $maximumNormalBallNumber = $lottery->no_of_ball - 1;
        }

        if ($lottery->pw_ball_start_from) {
            $maximumPowerBallNumber = $lottery->no_of_pw_ball;
        } else {
            $maximumPowerBallNumber = $lottery->no_of_pw_ball - 1;
        }

        if (count($request->winning_normal_ball) != $lottery->total_picking_ball) {
            throw ValidationException::withMessages(['error' => 'Total picking normal ball should be ' . $lottery->total_picking_ball]);
        }

        foreach ($request->winning_normal_ball as $normalBall) {
            if ($normalBall < $lottery->ball_start_from || $normalBall > $maximumNormalBallNumber) {
                $allInRange = false;
                break;
            }
        }

        if (!$allInRange) {
            throw ValidationException::withMessages(['error' => 'The winning normal balls should be between ' . $lottery->ball_start_from . ' - ' . $maximumNormalBallNumber]);
        }

        if ($lottery->has_power_ball) {

            if (count($request->winning_power_ball) != $lottery->total_picking_power_ball) {
                throw ValidationException::withMessages(['error' => 'Total picking power ball should be ' . $lottery->total_picking_power_ball]);
            }

            foreach ($request->winning_power_ball as $powerBall) {
                if ($powerBall < $lottery->pw_ball_start_from || $powerBall > $maximumPowerBallNumber) {
                    $allInRange = false;
                    break;
                }
            }

            if (!$allInRange) {
                throw ValidationException::withMessages(['error' => 'The winning power balls should be between ' . $lottery->pw_ball_start_from . ' - ' . $maximumPowerBallNumber]);
            }
        }
    }
}
