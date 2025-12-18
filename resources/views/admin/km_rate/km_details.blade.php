@extends('admin.admin_layout.admin_master_layout')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">KM Details for CFT Range: {{ $cft->from_cft }} - {{ $cft->to_cft }}  CFT Rate:  {{ $cft->cft_rate }}  CFT Profit:  {{ $cft->cft_profit }}  CFT Rate:  {{ $cft->cft_rate }}  CFT Rate Type:  {{ $cft->rate_type }}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <!-- Table of KM rates -->
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>From KM</th>
                                        <th>To KM</th>
                                        <th>Rate</th>
                                        <th>Rate Type</th>
                                        <th>Km Profit</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kmRates as $kmRate)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $kmRate->from_km }}</td>
                                        <td>{{ $kmRate->to_km }}</td>
                                        <td>{{ $kmRate->km_rate }}</td>
                                        <td>{{ $kmRate->rate_type == '0' ? 'Fixed' : 'Per KM' }}</td>
                                        <td>{{ $kmRate->km_profit }}</td>
                                        <td>
                                             <a href="{{ route('app.admin-kmRate.edit', $kmRate->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                            <!-- Delete button to remove KM Rate -->
                                            <form action="{{ route('app.admin-kmRate.destroy', $kmRate->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this KM Rate?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach

                                  <a href="{{ route('app.admin-kmRate.create') }}" class="btn btn-info btn-sm mb-4">Back</a>
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
