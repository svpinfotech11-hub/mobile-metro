@extends('admin.admin_layout.admin_master_layout')

@section('content')
<style>
    /* Table Styling */
    .table thead th {
        border-bottom-width: 2px;
        font-size: 14px;
        background-color: #f4f6f9; /* Light header background */
        color: #333;
        text-align: center;
    }
    .table-bordered td, .table-bordered th {
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

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">

        <div class="card shadow-sm">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Vendor Registration List</h3>
           
          </div>

          <div class="card-body table-responsive">

          <form method="GET" action="{{ route('admin.vendors.get-vendor') }}">
              <input type="text" name="full_name" value="{{ request('full_name') }}" placeholder="Search by name">

               <input type="text" name="email" value="{{ request('email') }}" placeholder="Search by email">

                <input type="text" name="mobile_no" value="{{ request('mobile_no') }}" placeholder="Search by Mobile">

                <input type="date" name="created_at" value="{{ request('created_at') }}">

              <button type="submit">Filter</button>
              <button type="button"><a href="{{ route('admin.vendors.get-vendor') }}">Reset</a></button>
            </form>
            {{-- Vendor Table --}}
            <table class="table table-bordered table-hover text-center align-middle">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Full Name</th>
                  <th>Business Name</th>
                  <th>Email</th>
                  <th>Phone No</th>
                  <th>Address</th>
                  <th>Business Type</th>
                  <th>Business Description</th>
                  <th>Experience (Years)</th>
                  <th>Service Areas</th>
                  <th>Created At</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($records as $index => $vendor)
                  <tr>
                    <td>{{ $loop->iteration + ($records->currentPage() - 1) * $records->perPage() }}</td>
                    <td>{{ $vendor->full_name }}</td>
                    <td>{{ $vendor->business_name ?? '-' }}</td>
                    <td>{{ $vendor->email ?? '-' }}</td>
                    <td>{{ $vendor->mobile_no }}</td>
                    <td>{{ $vendor->address ?? '-' }}</td>
                    <td>
                      @if($vendor->business_type)
                        {{ is_array(json_decode($vendor->business_type, true)) 
                            ? implode(', ', json_decode($vendor->business_type, true)) 
                            : $vendor->business_type }}
                      @else
                        -
                      @endif
                    </td>
                    <td>{{ $vendor->business_description ?? '-' }}</td>
                    <td>{{ $vendor->experience_years ?? '0' }}</td>
                    <td>{{ $vendor->service_areas ?? '-' }}</td>
                   <td>{{ \Carbon\Carbon::parse($vendor->created_at)->format('d-m-Y h:i A') }}</td>
                    <td>
                        <form action="{{ route('vendor.destroy', $vendor->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this vendor?');">
                            @csrf
                            @method('DELETE')s
                            <button type="submit" class="btn btn-danger btn-sm">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                
                @endforelse
              </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-3">
              {{ $records->links('pagination::bootstrap-5') }}
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>
@endsection
