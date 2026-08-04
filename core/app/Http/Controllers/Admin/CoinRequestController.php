<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\CoinRequest;
use App\Models\Transaction;

class CoinRequestController extends Controller
{
    public function log (){

        $pageTitle    = 'Coin Request';
        $coinRequests = CoinRequest::searchable(['user:username,email,mobile','request_number'])->with('user')->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.coin_request.list', compact('pageTitle', 'coinRequests'));
    }

    public function approve($id){

        $coinRequest         = CoinRequest::where('status',Status::COIN_REQUEST_PENDING)->with('user')->findOrFail($id);
        $coinRequest->status = Status::COIN_REQUEST_APPROVED;
        $coinRequest->save();

        $user           = $coinRequest->user;
        $user->balance += $coinRequest->amount;
        $user->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $coinRequest->amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge       = 0;
        $transaction->trx          = getTrx();
        $transaction->trx_type     = "+";
        $transaction->details      = showAmount($coinRequest->amount) . " coin added";
        $transaction->remark       = 'coin_added';
        $transaction->save();

        notify($user, "COIN_REQUEST_APPROVED", [
            'request_number' => $coinRequest->request_number,
            'username'       => $user->username,
            'time'           => showDateTime($coinRequest->created_at),
            'amount'         => showAmount($coinRequest->amount, currencyFormat:false),
        ]);

        $notify[] = ['success', gs('cur_text') . ' added successfully'];
        return back()->withNotify($notify);
    }
    }
