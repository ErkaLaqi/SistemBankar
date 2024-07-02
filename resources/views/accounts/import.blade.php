<div class="container">
    <div class="row">
        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <h4>Import Excel Data into Database using Laravel</h4>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (isset($errors) && $errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                {{$error}}
                            @endforeach
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
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="file">File</label>
                            <input type="file" name="file" id="file" class="form-control" required/>
                        </div>
                        <br>
                        <button type="submit" name="save_excel_data" class="btn btn-success mt-3">Import</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
