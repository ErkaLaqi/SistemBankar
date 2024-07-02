<?php

namespace App\Imports;

use App\Models\Account;
use App\Models\AccountTransaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Throwable;


class AccountTransactionImport implements
    ToModel,
    WithHeadingRow,
    SkipsOnError,
    WithValidation,
    SkipsOnFailure


{
    use Importable, SkipsErrors, SkipsFailures;



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

    /**
     * @param array $row
     *
     * @return Model|AccountTransaction|null
     * @throws \Exception
     */
    public function model(array $row): Model|AccountTransaction|null
    {
        //llogaris tipin e transaksionit
        $tipi_transaksionit = $this->TransactionType($row['iban_out'], $row['iban_in']);
        //marrim accountet nga te cilat ka ndodhur transaksioni
        $outgoing_account = Account::where('IBAN', $row['iban_out'])->first();
        $incoming_account = Account::where('IBAN', $row['iban_in'])->first();

        if (!$outgoing_account && !$incoming_account) {
            return null;
        }

        $vlera_transaksionit = $this->convertAmountToMatchCurrency($row['amount'], $row['currency'], $outgoing_account, $incoming_account);
    /*    return new AccountTransaction([
                    'outgoing_iban'    => $row['iban_out'],
                    'incoming_iban'     => $row['iban_in'],
                    'amount'       => $row['amount'],
                    'currency'     => $row['currency'],
                    'description'  => $row['description'],
                    'user_id'     => Auth::id(),
       ]);*/

        if (($tipi_transaksionit === 'INTERNAL' || $tipi_transaksionit === 'DALESE') && $outgoing_account->balance < $vlera_transaksionit) {
            throw new \Exception('Fonde te pamjaftueshme');
        }

$transaction = new AccountTransaction([
    'transaction_type'   => $tipi_transaksionit,
    'outgoing_iban'    => $row['iban_out'],
    'incoming_iban'     => $row['iban_in'],
    'amount'       => $vlera_transaksionit,
    'currency'     => $row['currency'],
    'description'  => $row['description'],
    'user_id'     => Auth::id(),

]);

if ($tipi_transaksionit === 'INTERNAL') {
    $incoming_account->balance += $vlera_transaksionit;
    $outgoing_account->save();
    $incoming_account->save();
} elseif ($tipi_transaksionit === 'DALESE') {
    $outgoing_account->balance -= $vlera_transaksionit;
    $outgoing_account->save();
} elseif ($tipi_transaksionit === 'HYRESE') {
    $incoming_account->balance += $vlera_transaksionit;
    $incoming_account->save();
}

return $transaction;
}
    /**
     *
     * @param string $iban_out
     * @param string $iban_in
     * @return string
     */
    private function TransactionType(string $iban_out, string $iban_in): string
    {
        $transaksionDales = Account::where('IBAN', $iban_out)->exists();
        $transaksionHyres = Account::where('IBAN', $iban_in)->exists();

        if ($transaksionDales && $transaksionHyres) {
            return 'INTERNAL';
        } elseif ($transaksionDales) {
            return 'DALESE';
        } elseif ($transaksionHyres) {
            return 'HYRESE';
        }

        return 'UNKNOWN';
    }

    private function convertAmountToMatchCurrency(float $vlera_transaksionit, string $currency, ?Account $outgoing_account = null, ?Account $incoming_account = null): float
    {
        if (empty($currency)) {
            return $vlera_transaksionit; // or handle this case as per your application logic
        }

        if ($outgoing_account && isset(self::CONVERSION_RATES[$outgoing_account->Monedha][$currency])) {
            $conversionRate = self::CONVERSION_RATES[$outgoing_account->Monedha][$currency];
            return $vlera_transaksionit * $conversionRate;
        } elseif ($incoming_account && isset(self::CONVERSION_RATES[$incoming_account->Monedha][$currency])) {
            $conversionRate = self::CONVERSION_RATES[$incoming_account->Monedha][$currency];
            return $vlera_transaksionit * $conversionRate;
        }

        return $vlera_transaksionit; // fallback if conversion rate is not found
    }
    public function rules(): array
    {
        // TODO: Implement rules() method.
        return [
            '*.iban_out'    => ['required', 'string'],
            '*.iban_in'     => ['required', 'string'],
            '*.amount'      => ['required', 'numeric'],
            '*.currency'    => ['required', 'in:ALL,EUR,USD,GBP'],
            '*.description' => [],
        ];
    }

}
