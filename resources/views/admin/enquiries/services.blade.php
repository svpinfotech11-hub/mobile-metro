@extends('admin.admin_layout.admin_master_layout')

@section('content')
<section class="content">
    <div class="container-fluid">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">User Services</h3>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Category</th>
                            <th>Sub Category</th>
                            <th>Service Name</th>
                            <th>Service Date</th>
                            <th>Service Location</th>
                            <th>Pick Up Location</th>
                            <th>Drop Location</th>
                            <th>Floor Number</th>
                            <th>Vehicle Number</th>
                            <th>Service Description</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($enquiries as $index => $enquiry)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $enquiry->category->name ?? '-' }}</td>
                            <td>{{ $enquiry->subCategory->sub_categoryname ?? '-' }}</td>
                            <td>{{ $enquiry->service_name ?? '-' }}</td>
                            <td>{{ $enquiry->service_date ? $enquiry->service_date->format('d M Y H:i:s') : '-' }}</td>
                            <td>{{ $enquiry->service_location ?? '-' }}</td>
                            <td>{{ $enquiry->pickup_location ?? '-' }}</td>
                            <td>{{ $enquiry->drop_location ?? '-' }}</td>
                            <td>{{ $enquiry->floor_number ?? '-' }}</td>
                            <td>{{ $enquiry->vehicle_number ?? '-' }}</td>
                            <td>{{ $enquiry->service_description ?? '-' }}</td>
                            <td>{{ $enquiry->created_at ? $enquiry->created_at->format('d M Y H:i:s') : '-' }}</td>
                            <td>
                                <form action="{{ route('enquiries.destroy', $enquiry->id) }}" method="POST"
                                    onsubmit="return confirm('Delete this enquiry?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">🗑</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center">No enquiries found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>

    </div>
</section>

@endsection