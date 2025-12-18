@extends('admin.admin_layout.admin_master_layout')

@section('content')


  <style>
    .btn-group-sm>.btn, .btn-sm {
    padding: 5px;
    font-size: 7px;
    line-height: 1.5;
    border-radius: .2rem;
    }i.fas.fa-trash-alt {
        font-size: 14px !important;
    }

    i.fa.fa-eye {
        font-size: 17px !important;
    }
  </style>
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
            <form action="{{ route('get-service-enquiry') }}" method="GET">
    <input type="text" placeholder="Search Order No" value="{{ request('order_no') }}" name="order_no">
    <input type="text" placeholder="Search Name" value="{{ request('customer_name') }}" name="customer_name">
    <input type="text" placeholder="Search Email" value="{{ request('email') }}" name="email">
    <input type="text" placeholder="Search Mobile" value="{{ request('mobile_no') }}" name="mobile_no">
    <input type="date" placeholder="Search Date" value="{{ request('created_date') }}" name="created_date">
    <input type="submit" value="Search">
    <button type="button">
        <a href="{{ route('get-service-enquiry') }}">Reset</a>
    </button>
</form>
 
            <table id="example2" class="table table-responsive table-bordered table-hover">
              <thead>
                <tr>
                    <th>#</th>
                  <th>Order No</th>
                  <th>Customer Name</th>
                   <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Service Description/Notes </th>
                  <!-- <th>Notes </th> -->
                  <th>Flat/Shop No</th>
                  <th>Service Location</th>
                  <th>Service Name</th>
                  <th>Service Date</th>
                  <th>Enquiry Created Date</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @if($enquiry_details)
                  @foreach($enquiry_details as $value)

                  @php
                   $customer = DB::table('tbl_customer') ->where('id', $value->customer_id)->first();
                  @endphp

                  <tr>
                     <td>{{ $value->id }}</td>
                    <td>{{$value->order_no }}</td>
                    <td>{{ $customer?->customer_name ?? 'N/A' }}</td>
                    <td>{{$customer->mobile_no ?? '' }}</td>
                    <td>{{$customer->email ?? '' }}</td>
                    <td>{{$value->service_description }}</td>
                    <!-- <td>{{$value->notes }}</td> -->
                    <td>{{$value->flat_no }}</td>
                    <td>{{$value->service_location }}</td>
                    <td>{{$value->service_name }}</td>
                  <td>{{ \Carbon\Carbon::parse($value->service_date)->timezone(config('app.timezone'))->format('d-m-Y h:i A') }}</td>
                  <td>{{ \Carbon\Carbon::parse($value->created_at)->timezone(config('app.timezone'))->format('d-m-Y h:i A') }}</td>
                    <td>
                    <form action="{{ route('get-enquiry-destroy', $value->id) }}" method="POST">
                      @csrf
                      @method('DELETE')
                      <input type="hidden" name="order_no" value="{{ $value->order_no }}">
                      <button onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </form>
                  </td>
                  </tr>
                  @endforeach
                @else
                  <tr>
                      <td colspan="10">NO DATA FOUND</td>
                  </tr>
                @endif  

                </tfoot>
            </table>
            {{$enquiry_details->links('pagination::bootstrap-5')}}
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