@extends('admin.admin_layout.admin_master_layout')

@section('content')

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{$title}}</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <!-- <form action="{{ route('app.admin-cftRate.create');}}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                     <div class="form-group">
                                        <label for="exampleInputEmail1">FROM CFT</label>
                                        <input type="text" name="from_cft[]" class="form-control" placeholder="Enter FROM CFT">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">TO CFT</label>
                                        <input type="text" name="to_cft" class="form-control" placeholder="Enter TO CFT">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">RATE</label>
                                        <input type="text" name="cft_rate" class="form-control" placeholder="ENTER CFT RATE">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">RATE TYPE</label>
                                       <select class="form-control select2" style="width: 100%;">
                                            <option value="">SELECT</option>
                                            <option value="1">FIXED</option>
                                            <option value="2">PER CFT</option>
                                        </select>
                                    </div>
                                </div>
                                 <div class="col-md-1 mt-4">
                                    <button class="btn btn-success btn-sm">+</button>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary btn-sm mt-4">Submit</button>
                                </div>
                            </div>
                    </form> -->
                    @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form action="{{ route('app.admin-kmRate.store') }}" method="POST">
                        @csrf
                        <table class="table table-bordered" id="dynamicTable">
                            <tr>
                                <th>CFT</th>
                                <th>From KM</th>
                                <th>To KM</th>
                                <th>Rate</th>
                                <th>KM Profit</th>
                                <th>Rate Type</th>
                                <th>Action</th>
                            </tr>
                            <tr>
                                <td>
                                    <select name="cft_id[]" class="form-control">
                                        <option value="">Select</option>
                                        @foreach($cft as $v)
                                        <option value="{{ $v->id }}">{{ $v->from_cft }} - {{ $v->to_cft }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" name="from_km[]" class="form-control" placeholder="Enter FROM KM" /></td>
                                <td><input type="text" name="to_km[]" class="form-control" placeholder="Enter TO KM" /></td>
                                <td><input type="text" name="km_rate[]" class="form-control" placeholder="Enter KM RATE" /></td>
                                <td><input type="text" name="km_profit[]" class="form-control" placeholder="Enter KM Profit" /></td>
                                <td>
                                    <select name="rate_type[]" class="form-control">
                                        <option value="">Select</option>
                                        <option value="0">Fixed</option>
                                        <option value="1">PER KM</option>
                                    </select>
                                </td>
                                <td><button type="button" id="add_form" class="btn btn-success">+</button></td>
                            </tr>
                            <!-- <body id="dynamicTable"></body> -->
                        </table>
                        <button type="submit" class="btn btn-primary ml-4 btn-sm mb-4">Submit</button>
                    </form>

                </div>
                <!-- /.card -->
            </div>

            <!-- /.card -->

        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <!-- <h3 class="card-title">{{ $title }}</h3> -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>CFT Range</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rates as $range => $rateGroup)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('app.admin-kmRate.showKmDetails', $rateGroup->first()->cft->id) }}">
                                            {{ $range }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <!-- Optionally add Edit or Delete buttons for each CFT range -->
                                            <form class="ml-2" action="{{ route('app.admin-kmRate.destroy', $rateGroup->first()->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>

    </div>
</section>




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        var i = 0;
        $("#add_form").click(function() {

            ++i;
            $("#dynamicTable").append(`
                <tr>
                <td>
                    <select name="cft_id[]" class="form-control">
                        <option value="">Select</option>
                        @foreach($cft as $v)
                            <option value="{{ $v->id }}">{{ $v->from_cft }} - {{ $v->to_cft }}</option>
                        @endforeach
                    </select>
    
                    </td>
                    <td><input type="text" name="from_km[]" class="form-control" /></td>
                    <td><input type="text" name="to_km[]" class="form-control" /></td>
                    <td><input type="text" name="km_rate[]" class="form-control" /></td>
                    <td><input type="text" name="km_profit[]" class="form-control" ></td>
                    <td>
                        <select name="rate_type[]" class="form-control">
                            <option value="">Select</option>
                            <option value="0">Fixed</option>
                            <option value="1">PER KM</option>
                        </select>
                    </td>
                    <td><button type="button" class="btn btn-danger remove-tr">X</button></td>
                </tr>
            `);
        });

        $(document).on('click', '.remove-tr', function() {
            $(this).closest('tr').remove();
        });
    });
</script>

@endsection