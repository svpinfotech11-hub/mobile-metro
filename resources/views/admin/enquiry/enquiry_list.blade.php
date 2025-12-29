@extends('admin.admin_layout.admin_master_layout')

@section('content')

<style>
  .btn-group-sm>.btn,
  .btn-sm {
    padding: 5px;
    font-size: 7px;
    line-height: 1.5;
    border-radius: .2rem;
  }
a.btn.btn-info.btn-sm.dsf {
    width: 26px;
    font-size: 16px;
    height: 30px;
}
  i.fas.fa-trash-alt {
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
            <!--<form action="{{route('app.admin-enquiry.index');}}" method="GET">-->
            <!--    <input type="text" placeholder="search Order No" value="{{ request('order_no')}}" name="order_no">-->
            <!--    <input type="submit" name="submit">-->
            <!--    <button type="button"><a href="{{ route('app.admin-enquiry.index');}}">Reset</a></button>-->
            <!--</form>-->

            <form action="{{ route('app.admin-enquiry.index') }}" method="GET">
              <input type="text" placeholder="Search Order No" value="{{ request('order_no') }}" name="order_no">
              <input type="text" placeholder="Search Name" value="{{ request('customer_name') }}" name="customer_name">
              <input type="text" placeholder="Search Email" value="{{ request('email') }}" name="email">
              <input type="text" placeholder="Search Mobile" value="{{ request('mobile_no') }}" name="mobile_no">
              <input type="date" placeholder="Search Date" value="{{ request('created_at') }}" name="created_at">
              <input type="submit" value="Search">
              <button type="button">
                <a href="{{ route('app.admin-enquiry.index') }}">Reset</a>
              </button>
            </form>

            <!-- <table class="table table-responsive table-bordered table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Order No</th>
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Pickup Location</th>
                  <th>Drop Location</th>
                  <th>Flat/Shop No</th>
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
                  <td>{{$customer->customer_name ?? '' }}</td>
                  <td>{{$customer->mobile_no ?? '' }}</td>
                  <td>{{$customer->email ?? '' }}</td>
                  <td>{{$value->pickup_location }} </td>
                  <td>{{$value->drop_location }}</td>
                  <td>{{$value->flat_shop_no }}</td>
                  <td>
                    <a href="{{ route('app.admin-enquiry.show',$value->id)}}"><i class="fa fa-eye" aria-hidden="true" class="text-danger"></i></a>
                  
                      <form action="{{ route('admin-packers-movers-destroy', $value->id) }}" method="POST" style="display:inline;" class="ml-1">
                      @csrf
                      @method('DELETE')
                      <button onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">
                          <i class='fas fa-trash-alt' style='font-size:12px;'></i>
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
            </table> -->

            <table class="table table-responsive table-bordered table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Order No</th>
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Pickup Location</th>
                  <th>Drop Location</th>
                  <th>Payment Detail</th>
                  <th>Created At</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse($enquiry_details as $value)
                @php
                $customer = $value->customer;
                $payment = $value->payments->first(); // Assuming one payment per enquiry
                @endphp
                <tr>
                  <td>{{ $value->id }}</td>
                  <td>{{ $value->order_no }}</td>
                  <td>{{ $customer->customer_name ?? '-' }}</td>
                  <td>{{ $customer->mobile_no ?? '-' }}</td>
                  <td>{{ $customer->email ?? '-' }}</td>
                  <td>{{ $value->pickup_location ?? '-' }}</td>
                  <td>{{ $value->drop_location ?? '-' }}</td>
                  <td>
                    @if($payment)
                    <p>Order ID: {{ $payment->razorpay_order_id ?? '-' }}</p>
                    <p>Transaction ID: {{ $payment->razorpay_payment_id ?? '-' }}</p>
                    <p>Status: {{ $payment->payment_status ?? '-' }}</p>
                    <p>Total Amount: ₹{{ $payment->total_amount ?? 0 }}</p>
                    <p>Paid Amount: ₹{{ $payment->amount ?? 0 }}</p>
                    <p>Remaining Amount: ₹{{ $payment->remaining_amount ?? 0 }}</p>
                    <p>Method: {{ $payment->payment_method ?? '-' }}</p>
                    <p>Payment Date: {{ optional($payment->payment_date) ? \Carbon\Carbon::parse($payment->payment_date)->format('d-m-Y') : 'N/A' }}</p>
                    @else
                    <p>No payment data</p>
                    @endif
                  </td>
                  <td>{{ \Carbon\Carbon::parse($value->created_at)->format('d-m-Y') }}</td>
                  <td>
                    <a href="{{ route('app.admin-enquiry.show', $value->id) }}">
                      <i class="fa fa-eye text-primary" style="font-size:16px;"></i>
                    </a>
                  <a href="{{ route('admin.enquiry.field-report', $value->id) }}" class="btn btn-info btn-sm dsf">
                    <i class="fa fa-file-alt" aria-hidden="true"></i>
                  </a>

                    <form action="{{ route('admin-packers-movers-destroy', $value->id) }}" method="POST" style="display:inline;" class="ml-1">
                      @csrf
                      @method('DELETE')
                      <button onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">
                        <i class='fas fa-trash-alt' style='font-size:12px;'></i>
                      </button>
                    </form>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="10" class="text-center">NO DATA FOUND</td>
                </tr>
                @endforelse
              </tbody>
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