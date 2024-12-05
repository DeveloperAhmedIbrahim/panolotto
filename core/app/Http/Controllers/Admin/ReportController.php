<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lottery;
use App\Models\NotificationLog;
use App\Models\Phase;
use App\Models\Transaction;
use App\Models\UserLogin;
use App\Models\UserPick;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function transaction(Request $request, $userId = null)
    {
        $pageTitle    = 'Transaction Logs';
        $remarks      = Transaction::distinct('remark')->orderBy('remark')->get('remark');
        $transactions = Transaction::searchable(['trx', 'user:username'])->filter(['trx_type', 'remark'])->dateFilter()->orderBy('id', 'desc')->with('user');
        
        if ($userId) {
            $transactions = $transactions->where('user_id', $userId);
        }
        $transactions = $transactions->paginate(getPaginate());

        return view('admin.reports.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }

    public function loginHistory(Request $request)
    {
        $pageTitle = 'User Login History';
        $loginLogs = UserLogin::orderBy('id', 'desc')->searchable(['user:username'])->dateFilter()->with('user')->paginate(getPaginate());
        return view('admin.reports.logins', compact('pageTitle', 'loginLogs'));
    }

    public function loginIpHistory($ip)
    {
        $pageTitle = 'Login by - ' . $ip;
        $loginLogs = UserLogin::where('user_ip', $ip)->orderBy('id', 'desc')->with('user')->paginate(getPaginate());
        return view('admin.reports.logins', compact('pageTitle', 'loginLogs', 'ip'));
    }

    public function notificationHistory(Request $request)
    {
        $pageTitle = 'Notification History';
        $logs = NotificationLog::orderBy('id', 'desc')->searchable(['user:username'])->dateFilter()->with('user')->paginate(getPaginate());
        return view('admin.reports.notification_history', compact('pageTitle', 'logs'));
    }

    public function emailDetails($id)
    {
        $pageTitle = 'Email Details';
        $email = NotificationLog::findOrFail($id);
        return view('admin.reports.email_details', compact('pageTitle', 'email'));
    }


    public function ticketPurchaseHistory()
    {
        $pageTitle = "Ticket Purchase History";
        $userPicks = UserPick::paid()->with('user', 'phase.lottery', 'pickedTickets')->searchable(['user:username'])->dateFilter();

        if (request()->lottery_id) {
            $lotteryId = request()->lottery_id;
            $userPicks = $userPicks->whereHas('phase', function ($phase) use ($lotteryId) {
                $phase->where('lottery_id', $lotteryId);
            });
        }

        if (request()->phase_no) {
            $userPicks = $userPicks->filter(['phase:phase_no']);
        }

        $userPicks = $userPicks->latest('id')->paginate(getPaginate());
        $lotteries = Lottery::all();
        $maxPhase = Phase::max('phase_no');
        return view('admin.reports.ticket_purchase_history', compact('pageTitle', 'userPicks', 'lotteries', 'maxPhase'));
    }

    public function winningHistory()
    {
        $pageTitle = "Winning History";
        $phases    = Phase::completed()->with('lottery:id,name')->withSum('winners as total_prize', 'prize_money')->searchable(['lottery:name'])->dateFilter('draw_date')->paginate(getPaginate());

        return view('admin.reports.winning.history', compact('pageTitle', 'phases'));
    }

    public function winningDetail($id)
    {
        $phase     = Phase::completed()->with('winners.user', 'winners.pickedTicket', 'lottery:id,name,has_power_ball')->withSum('userPicks as sold_amount', 'amount')->findOrFail($id);
        $pageTitle = "Winner's of " . $phase->lottery->name . "(" . showPhase($phase->phase_no) . ")";

        return view('admin.reports.winning.details', compact('pageTitle', 'phase'));
    }
}
