<?php

namespace App\Http\Controllers;

use App\Imports\AccountTransactionImport;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class AccountTransactionImportController extends Controller
{

    public function show(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('accounts.index');
    }

    public function store(Request $request): Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);
        $file = $request->file('file')->store('import');
        $import = new AccountTransactionImport;
        $import->import($file);
        if($import->failures()->isNotEmpty()){
            return back()->withFailures($import->failures());
        }

        return back()->withStatus('Import was Successful');

    }
}
