<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Lottery;
use App\Models\Phase;
use App\Models\PickedTicket;
use App\Models\Transaction;
use App\Models\UserMultiDraw;
use App\Models\UserPick;
use App\Models\Winner;
use Illuminate\Http\Request;

class LotteryController extends Controller
{
    public function purchaseHistory()
    {
        $pageTitle = "Lottery Purchase History";
        $userPicks = UserPick::paid()->where('user_id', auth()->id())->with('pickedTickets', 'phase.lottery')->orderBy('id', 'desc')->paginate(getPaginate());
        return view('Template::user.lottery.purchase_history', compact('pageTitle', 'userPicks'));
    }

    public function pendingDraw()
    {
        $pageTitle = 'Pending Draw';
        $phases = Phase::active()->winnerNotSet()->where('draw_date', '<=', now())->whereHas('userPicks', function ($query) {
            $query->paid()->where('user_id', auth()->id());
        })->with('lottery:id,name')->orderBy('draw_date', 'desc')->paginate(getPaginate());

        return view('Template::user.lottery.pending_draw', compact('pageTitle', 'phases'));
    }

    public function winningHistory()
    {
        $pageTitle = 'Winning History';
        $winners = Winner::where('user_id', auth()->id())->with('phase.lottery')->paginate(getPaginate());
        return view('Template::user.lottery.winning_history', compact('pageTitle', 'winners'));
    }

    public function pick(Request $request, $id)
    {
        $lottery = Lottery::active()->with(['activePhase', 'multiDrawOptions' => function ($query) {
            $query->where('status', Status::ENABLE);
        }])->whereHas('activePhase')->findOrFail($id);

        $this->pickingValidation($lottery, $request);

        if ($request->phase_id != @$lottery->activePhase->id) {
            $notify[] = ['error', 'The phase is invalid, please try an active phase'];
            return back()->withNotify($notify);
        }

        $user         = auth()->user()->load('referrer');
        $totalAmount  = $ticketAmount = $lottery->price * count($request->ticket);
        $option       = null;
        $discount     = 0;

        if ($lottery->has_multi_draw && $request->multi_draw_option_id) {
            $option       = $lottery->multiDrawOptions->where('id', $request->multi_draw_option_id)->first();
            $totalAmount  = $ticketAmount * $option->total_draw;
            $discount     = $totalAmount * $option->discount / 100;
            $totalAmount  = $totalAmount - $discount;

            $ticketAmount = $totalAmount / $option->total_draw;
        }

        if ($request->payment_via == 'balance' && $user->balance < $totalAmount) {
            $notify[] = ['error', 'You don\'t have sufficient coin'];
            return back()->withNotify($notify);
        }

        $userPick               = new UserPick();
        $userPick->user_id      = $user->id;
        $userPick->phase_id     = $lottery->activePhase->id;
        $userPick->amount       = $ticketAmount;
        $userPick->save();

        $this->insertPickedTickets($request, $userPick);

        if ($lottery->has_multi_draw && $request->multi_draw_option_id) {
            $userMultiDraw                 = new UserMultiDraw();
            $userMultiDraw->user_id        = $user->id;
            $userMultiDraw->lottery_id     = $lottery->id;
            $userMultiDraw->user_pick_id   = $userPick->id;
            $userMultiDraw->total_draw     = @$option->total_draw;
            $userMultiDraw->remaining_draw = @$option->total_draw - 1;
            $userMultiDraw->amount         = $totalAmount;
            $userMultiDraw->discount       = $discount;
            $userMultiDraw->total_amount   = $totalAmount + $discount;
            $userMultiDraw->advance        = $totalAmount - $ticketAmount;
            $userMultiDraw->last_draw_date = $lottery->activePhase->draw_date;
            $userMultiDraw->save();
        }

        if ($request->payment_via == 'balance') {
            $user->balance -= $totalAmount;
            $user->save();

            $transaction               = new Transaction();
            $transaction->user_id      = $user->id;
            $transaction->amount       = $totalAmount;
            $transaction->post_balance = $user->balance;
            $transaction->charge       = 0;
            $transaction->trx_type     = '-';
            $transaction->details      = 'Payment for purchase ticket';
            $transaction->trx          = getTrx();
            $transaction->remark       = 'payment';
            $transaction->save();

            $userPick->status = Status::PAYMENT_SUCCESS;
            $userPick->save();

            notify($user, 'PURCHASE_COMPLETE', [
                'trx'            => $transaction->trx,
                'lottery'        => $lottery->name,
                'price'          => showAmount($lottery->price, exceptZeros: true, currencyFormat:false),
                'total_ticket'   => count($request->ticket),
                'total_price'    => showAmount($totalAmount, currencyFormat:false),
                'post_balance'   => showAmount($user->balance, currencyFormat:false)
            ]);

            if (gs('lottery_purchase_commission')) {
                levelCommission($user, $totalAmount, 'lottery_purchase_commission', $transaction->trx);
            }
        } else {
            return to_route('user.deposit.index', ['user_pick' => encrypt($userPick->id)]);
        }

        $notify[] = ['success', 'Ticket purchased successfully'];
        return back()->withNotify($notify);
    }

    private function insertPickedTickets($request, $userPick)
    {
        $tickets = [];
        foreach ($request->ticket as $key => $ticket) {
            $normalBalls = $ticket['normal_ball'];
            $powerBalls = $ticket['power_ball'] ?? [];

            sort($normalBalls);
            sort($powerBalls);

            $tickets[$key]['user_pick_id'] = $userPick->id;
            $tickets[$key]['normal_balls'] = json_encode(array_map('intVal', $normalBalls));
            $tickets[$key]['power_balls']  = json_encode(array_map('intVal', $powerBalls));
            $tickets[$key]['created_at']   = now();
            $tickets[$key]['updated_at']   = now();
        }

        $tickets = array_values($tickets);
        PickedTicket::insert($tickets);
    }

    private function pickingValidation($lottery, $request)
    {
        $minimumTicket = min($lottery->line_variations);
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

        $powerBallValidation = 'nullable';
        if ($lottery->has_power_ball) {
            $powerBallValidation = 'required';
        }

        $validation = [
            'phase_id'               => 'required',
            'payment_via'            => 'required|in:balance,direct',
            'entry_type'             => 'nullable|in:1,2',
            'ticket'                 => 'required|array|min:' . $minimumTicket,
            'ticket.*.normal_ball'   => 'required|array|size:' . $lottery->total_picking_ball,
            'ticket.*.power_ball'    => $powerBallValidation . '|array|size:' . $lottery->total_picking_power_ball,
            'ticket.*.normal_ball.*' =>  'required|numeric|between:' . $lottery->ball_start_from . ',' . $maximumNormalBallNumber,
            'ticket.*.power_ball.*'  => 'required|numeric|between:' . $lottery->pw_ball_start_from . ',' . $maximumPowerBallNumber
        ];

        $message = [
            'phase_id.required'               => 'The phase field is required',
            'payment_via.in'                  => 'The payment via should be in balance or direct',
            'ticket.min'                      => 'The ticket must be at least ' . $minimumTicket,
            'ticket.*.normal_ball.required'   => 'Each ticket should have ' . $lottery->total_picking_ball . ' normal balls selected',
            'ticket.*.normal_ball.size'       => 'The normal ball must be ' . $lottery->total_picking_ball,
            'ticket.*.power_ball.required'    => 'Each ticket should have ' . $lottery->total_picking_power_ball . ' power balls selected',
            'ticket.*.power_ball.size'        => 'The power ball must be ' . $lottery->total_picking_power_ball,
            'ticket.*.normal_ball.*.between'  => 'The normal ball must be between ' . $lottery->ball_start_from . ' and ' . $maximumNormalBallNumber,
            'ticket.*.power_ball.*.between'   => 'The power ball must be between ' . $lottery->pw_ball_start_from . ' and ' . $maximumPowerBallNumber
        ];

        if ($lottery->has_multi_draw && $request->entry_type == 2) {
            $optionIds                 = $lottery->multiDrawOptions->pluck('id')->toArray();
            $multiDrawOptionValidation = ['multi_draw_option_id' => 'required|integer|in:' . implode(',', $optionIds)];
            $validation                = array_merge($validation,  $multiDrawOptionValidation);
        }

        $request->validate($validation, $message);
    }
}
