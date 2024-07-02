<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountTransaction;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AccountTransactionController extends Controller
{
    public function getTransactions($accountId)
    {
        $account = Account::findOrFail($accountId);

        if (request()->ajax()) {
            $transactions = AccountTransaction::where('outgoing_iban', $account->IBAN)
                ->orWhere('incoming_iban', $account->IBAN)
                ->with('user')
                ->get();

            return DataTables::of($transactions)
                ->addColumn('user', function ($transaction) {
                    return $transaction->user ? $transaction->user->name : 'N/A';
                })
                ->make(true);
        }

        return view('accounts.transactions', compact('account'));
    }
}

