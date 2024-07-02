<!DOCTYPE html>
<html>
<head>
    <title>Transfer Transactions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="py-5">
        <h2>Levizje e transaksioneve brenda per brenda llogarive bankare </h2>
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('accounts.transfer') }}">
            @csrf
            <div class="form-group">
                <label for="outgoing_account">Outgoing Account</label>
                <select class="form-control" id="outgoing_account" name="outgoing_account">
                    @foreach ($accounts as $account)
                        <option value="{{ $account->IBAN }}">{{ $account->account_name }} ({{ $account->IBAN }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="incoming_account">Incoming Account</label>
                <select class="form-control" id="incoming_account" name="incoming_account">
                    @foreach ($accounts as $account)
                        <option value="{{ $account->IBAN }}">{{ $account->account_name }} ({{ $account->IBAN }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="amount">Vlera e Levizjes</label>
                <input type="number" class="form-control" id="amount" name="amount" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="description">Pershkrimi</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <br>
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>
</div>
</body>
</html>
