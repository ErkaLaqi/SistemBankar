<!DOCTYPE html>
<html>
<head>
    <title>Document Excel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <h4>Import Excel Data into database using Laravel</h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
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
                    <form action="{{ url('import_excel') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="file">File</label>
                            <input type="file" name="excel_file" id="file" class="form-control"/>
                        </div>

                        <button type="submit" name="save_excel_data" class="btn btn-primary mt-3">Import</button>
                    </form>

                <div class="mt-4">
                         <h2>Bank Accounts</h2>

                    <table id="example" class="display" style="width:100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Emri i llogarise</th>
                            <th>IBAN</th>
                            <th>Bilanci</th>
                            <th>Monedha</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Emri i llogarise</th>
                            <th>IBAN</th>
                            <th>Bilanci</th>
                            <th>Monedha</th>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    $(document).ready(function() {
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'account_name'},
                    {data: 'IBAN', name: 'IBAN'},
                    {data: 'balance', name: 'balance'},
                    {data: 'currency', name: 'currency'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        });
    });
</script>
</body>
</html>
