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
                        <a href="{{ route('app.admin-kmRate.create'); }}" class="btn btn-success btn-xs">+ ADD</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>From KM</th>
                                    <th>To KM</th>
                                    <th>Rate</th>
                                    <th>Rate Type</th>
                                    <th>KM Profit</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rates as $rate)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $rate->from_km }}</td>
                                    <td>{{ $rate->to_km }}</td>
                                    <td>{{ $rate->km_rate }}</td>
                                    <td>{{ $rate->km_profit }}</td>
                                    <td>@if($rate->rate_type == '0') fixed @else  PER BOX @endif </td>
                                    <td>
                                        <form action="{{ route('app.admin-kmRate.destroy', $rate->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm">Delete</button>
                                        </form>
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
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection