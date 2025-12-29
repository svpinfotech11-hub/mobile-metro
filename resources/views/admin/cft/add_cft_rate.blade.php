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

                    <form action="{{ route('app.admin-cftRate.store') }}" method="POST">
                        @csrf
                        <table class="table table-bordered" id="dynamicTable">
                            <tr>
                                <th>From CFT</th>
                                <th>To CFT</th>
                                <th>Rate</th>
                                <th>CFT Profit</th>
                                <th>Rate Type</th>
                                <th>Action</th>
                            </tr>
                            <tr>
                                <td><input type="number" name="from_cft[]" class="form-control" placeholder="Enter FROM CFT" /></td>
                                <td><input type="number" name="to_cft[]" class="form-control" placeholder="Enter TO CFT" /></td>
                                <td><input type="number" name="cft_rate[]" class="form-control" placeholder="Enter CFT RATE" /></td>
                                <td><input type="number" name="cft_profit[]" class="form-control" placeholder="Enter CFT Profit" /></td>
                                <td>
                                    <select name="rate_type[]" class="form-control">
                                        <option value="">Select</option>
                                        <option value="0">Fixed</option>
                                        <option value="1">PER CFT</option>
                                    </select>
                                </td>
                                <td><button type="button" id="add_form" class="btn btn-success">+</button></td>
                            </tr>
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
                    <!-- <div class="card-header">
                        <h3 class="card-title">{{ $title }}</h3>
                        <a href ="{{ route('app.admin-cftRate.create');}}" class="btn btn-success btn-xs" style="float: inline-end;">+ ADD</a>
                    </div> -->
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>From CFT</th>
                                    <th>To CFT</th>
                                    <th>Rate</th>
                                    <th>CFT Profit</th>
                                    <th>Rate Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rates as $rate)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $rate->from_cft }}</td>
                                    <td>{{ $rate->to_cft }}</td>
                                    <td>{{ $rate->cft_rate }}</td>
                                    <td>{{ $rate->cft_profit }}</td>
                                    <td>@if($rate->rate_type == '0') fixed @else PER BOX @endif </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('app.admin-cftRate.edit', $rate->id)  }}" class="btn btn-primary btn-sm"> Edit</a>
                                            <form action="{{ route('app.admin-cftRate.destroy', $rate->id) }}" method="POST" class="ml-2">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
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
 <td><input type="text" name="from_cft[]" class="form-control" placeholder="Enter FROM CFT" /></td>
                                <td><input type="text" name="to_cft[]" class="form-control" placeholder="Enter TO CFT" /></td>
                                <td><input type="text" name="cft_rate[]" class="form-control" placeholder="Enter CFT RATE" /></td>
                                <td><input type="text" name="cft_profit[]" class="form-control" placeholder="Enter CFT Profit" /></td>
                    <td>
                        <select name="rate_type[]" class="form-control">
                            <option value="">Select</option>
                            <option value="fixed">Fixed</option>
                            <option value="variable">Per CFT</option>
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
</script> @endsection