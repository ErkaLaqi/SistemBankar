<!DOCTYPE html>
<html>
<head>
    <title>Bank Accounts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">


</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <h4>Import Excel Data into Database using Laravel</h4>
                </div>
                <div class="py-12">
                    <div>
                        <div class="card-body">
                            <h3 class="font-semibold text-xl text-gray-800 leading-tight ">
                                {{ __('Ngarko Transaksion') }}
                            </h3>
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            @if(isset($errors) && $errors->any())
                                <div class="alert alert-danger">
                                    @foreach($errors->all() as $error)
                                        {{$error}}
                                    @endforeach
                                </div>
                            @endif


                            @if(session()->has('failures'))
                                <table class="table table-danger">
                                    <tr>
                                        <th>Row</th>
                                        <th>Attributes</th>
                                        <th>Errors</th>
                                        <th>Value</th>
                                    </tr>
                                    @foreach(session()->get('failures') as $validation)
                                        <tr>
                                            <td>{{$validation->row()}}</td>
                                            <td>{{$validation->attribute()}}</td>
                                            <td>
                                                <ul>
                                                    @foreach($validation->errors() as $e)
                                                        <li>{{$e}}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td>{{$validation->values()[$validation->attribute()]}}</td>
                                        </tr>
                                    @endforeach
                                </table>

                            @endif
                            <form action="/accounts/import" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <input type="file" name="file" id="file" required>
                                    <button type="submit" class="btn btn-success btn-sm">Import</button>
                                </div>
                            </form>
                            <br>
                            <a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary  transfer">Transfer</a>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h2>Bank Accounts</h2>
                        <table id="example" class="display" style="width:100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Account Name</th>
                                <th>IBAN</th>
                                <th>Balance</th>
                                <th>Currency</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Account Name</th>
                                <th>IBAN</th>
                                <th>Balance</th>
                                <th>Currency</th>
                                <th>Action</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {
        var table = $('#example').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('accounts.index') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'account_name', name: 'account_name'},
                {data: 'IBAN', name: 'IBAN'},
                {data: 'balance', name: 'balance'},
                {data: 'currency', name: 'currency'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    });
</script>
<script>

    $(document).on('click', '.transaksione', function(){
        var accountId = $(this).data('id');
        window.location.href = '/accounts/' + accountId + '/transactions';
    });

    $(document).on('click', '.transfer', function(){
        window.location.href = '/accounts/transfer';
    });
</script>
</body>
</html>
