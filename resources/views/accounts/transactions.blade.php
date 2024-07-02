<!DOCTYPE html>
<html>
<head>
    <title>Transaksionet ne llogari</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>
<body>
<div class="py-12">
    <div>
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <br>
            <h5>Transaksionet per llogarine: {{ $account->account_name }}</h5>
            <br>
            <table id="transactions-table" class="table table-striped table-bordered" style="width:100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipi i transaksionit</th>
                    <th>IBAN Out</th>
                    <th>IBAN In</th>
                    <th>Shuma</th>
                    <th>Monedha</th>
                    <th>Pershkrimi</th>
                    <th>Koha e kryerjes</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>ID</th>
                    <th>Tipi i transaksionit</th>
                    <th>IBAN Out</th>
                    <th>IBAN In</th>
                    <th>Shuma</th>
                    <th>Monedha</th>
                    <th>Pershkrimi</th>
                    <th>Koha e kryerjes</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        $('#transactions-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('accounts.transactions', $account->id) }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'transaction_type', name: 'transaction_type'},
                {data: 'outgoing_iban', name: 'outgoing_iban'},
                {data: 'incoming_iban', name: 'incoming_iban'},
                {data: 'amount', name: 'amount'},
                {data: 'currency', name: 'currency'},
                {data: 'description', name: 'description'},
                {data: 'created_at', name: 'created_at'}
            ]
        });
    });
</script>
</body>
</html>
