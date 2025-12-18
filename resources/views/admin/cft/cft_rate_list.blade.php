@extends('admin.admin_layout.admin_master_layout')
@section('content')

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $data['title'] }}</h3>
                        <a href ="{{ route('app.admin-cftRate.create');}}" class="btn btn-success btn-xs" style="float: inline-end;">+ ADD</a>
                    </div>
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
                                    <td>@if($rate->rate_type == '0') fixed @else  PER CFT @endif </td>
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
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection