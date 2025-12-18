@extends('admin.admin_layout.admin_master_layout')
<style>
  /* Table Styling */
  .table thead th {
    border-bottom-width: 2px;
    font-size: 14px;
    background-color: #f4f6f9;
    /* Light header background */
    color: #333;
    text-align: center;
  }

  .table-bordered td,
  .table-bordered th {
    border: 1px solid #dee2e6;
    font-size: 14px;
    vertical-align: middle;
  }

  .table-hover tbody tr:hover {
    background-color: #f9f9f9;
  }

  .card-header {
    background-color: #007bff;
    color: white;
  }

  .card-title {
    font-weight: 600;
  }

  .pagination {
    justify-content: center;
  }
</style>
@section('content')
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">{{ $title }}</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <form method="GET" action="{{ route('admin.customer-list') }}">
              <input type="text" name="customer_name" value="{{ request('customer_name') }}" placeholder="Search by name">

              <input type="text" name="email" value="{{ request('email') }}" placeholder="Search by email">

              <input type="text" name="mobile_no" value="{{ request('mobile_no') }}" placeholder="Search by Mobile">

              <input type="date" name="created_at" value="{{ request('created_at') }}">

              <button type="submit">Filter</button>
              <button type="button"><a href="{{ route('admin.customer-list') }}">Reset</a></button>
            </form>

            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Customer Name</th>
                  <th>Email</th>
                  <th>Phone No</th>
                  <th>Pincode</th>
                  <th>City</th>
                  <th>State</th>
                  <th>Created At</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>

                @if($customer)
                @foreach($customer as $c)
                <tr>
                  <td>{{$c->id}}</td>
                  <td>{{$c['customer_name']}}</td>
                  <td>{{$c['email']}}</td>
                  <td>{{$c['mobile_no']}}</td>
                  <td>{{$c['pincode']}}</td>
                  <td>{{$c['city']}}</td>
                  <td>{{$c['state']}}</td>
                  <td>{{ \Carbon\Carbon::parse($c->created_at)->timezone(config('app.timezone'))->format('d-m-Y h:i A') }}</td>
                  <td>
                    <form action="{{ route('destroy', $c->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this vendor?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-sm">
                        Delete
                      </button>
                    </form>
                  </td>
                  <td>
                    @if(!$c->is_blocked)
                    <form action="{{ route('admin.customer.block', $c->id) }}" method="POST" style="display:inline;">
                      @csrf
                      <button type="submit" class="btn btn-warning btn-sm"
                        onclick="return confirm('Are you sure you want to block this user?');">
                        Block
                      </button>
                    </form>
                    @else
                    <form action="{{ route('admin.customer.unblock', $c->id) }}" method="POST" style="display:inline;">
                      @csrf
                      <button type="submit" class="btn btn-success btn-sm"
                        onclick="return confirm('Unblock this user?');">
                        Unblock
                      </button>
                    </form>
                    @endif
                  </td>
                </tr>
                @endforeach
                @else
                <tr>
                  <td>NO DATA FOUND</td>
                </tr>
                @endif
                </tfoot>
            </table>
            {{ $customer->links('pagination::bootstrap-5') }}
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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">