<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class AccountController extends Controller
{
    private const CONVERSION_RATES = [
        'EUR' => [
            'USD' => 1.08,
            'GBP' => 0.85,
            'EUR' => 1,
        ],
        'USD' => [
            'EUR' => 0.94,
            'GBP' => 0.8,
            'USD' => 1,
        ],
        'GBP' => [
            'EUR' => 1.19,
            'USD' => 1.27,
            'GBP' => 1,
        ],
    ];
    public function index(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|JsonResponse|\Illuminate\Contracts\Foundation\Application
    {
        if ($request->ajax()) {
            $data = Account::all();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm transaksione">Transaksionet</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('accounts.index');
    }
    public function showTransferForm(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $accounts = Account::all();
        return view('accounts.transfer', compact('accounts'));
    }

    public function transfer(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'outgoing_account' => 'required|exists:accounts,IBAN',
            'incoming_account' => 'required|exists:accounts,IBAN|different:outgoing_account',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);


        $outgoingAccount = Account::where('IBAN', $request->outgoing_account)->first();
        $incomingAccount = Account::where('IBAN', $request->incoming_account)->first();


        $amount = $request->amount;

        if ($outgoingAccount->balance < $amount) {
            return redirect()->back()->withErrors(['amount' => 'Fonde te pamjaftueshme ne llogarine dalese']);
        }

        $amountInDestinationCurrency = $this->convertAmountToCurrency($amount, $outgoingAccount->currency, $incomingAccount->currency);

        $outgoingAccount->balance -= $amount;
        $incomingAccount->balance += $amountInDestinationCurrency;

        $outgoingAccount->save();
        $incomingAccount->save();

        AccountTransaction::create([
            'transaction_type' => 'INTERNAL',
            'outgoing_iban' => $request->outgoing_account,
            'incoming_iban' => $request->incoming_account,
            'amount' => $amount,
            'currency' => $outgoingAccount->currency,
            'description' => $request->description,
            'user_id' => Auth::id(),

        ]);
        return back()->withStatus('Funds transferred successfully.');
    }

    private function convertAmountToCurrency(float $amount, string $outgoingCurrency, string $incomingCurrency): float
    {
        if ($outgoingCurrency === $incomingCurrency) {
            return $amount;
        }

        return $amount * (self::CONVERSION_RATES[$outgoingCurrency][$incomingCurrency] ?? 1.0);
    }
}
