@extends('admin.admin_layout.admin_master_layout')

@section('content')


<section class="content">
    <div class="container-fluid">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">User Enquiries & Payments</h3>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Category</th>
                            <th>Sub Category</th>
                            <th>Services</th>
                            <th>Product Sub Categories</th>
                            <th>Products</th>
                            <th>Km Distance</th>
                            <th>Total Amount</th>
                            <th>Paid Amount</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>


                    <tbody>
                        @forelse($enquiries as $index => $enquiry)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>{{ $enquiry->category->name ?? '-' }}</td>

                            <td>{{ $enquiry->subCategory->sub_categoryname ?? '-' }}</td>

                            {{-- SERVICES --}}
                            <td>
                                @if($enquiry->services->count())
                                @foreach($enquiry->services as $service)
                                <span class="badge bg-info mb-1">{{ $service->service_name }}</span><br>
                                @endforeach
                                @elseif($enquiry->service_name)
                                <span class="badge bg-info">{{ $enquiry->service_name }}</span>
                                @else
                                -
                                @endif
                            </td>
                            <td>{{ $enquiry['km_distance'] }}</td>

                            {{-- PRODUCT SUBCATEGORIES --}}
                            <td>
                                @forelse($enquiry->productSubcategories as $psc)
                                <span class="badge bg-secondary mb-1">{{ $psc->subcat_name }}</span><br>
                                @empty
                                -
                                @endforelse
                            </td>

                            {{-- PRODUCTS --}}

                            <td>
                                @if(!empty($enquiry->products_item))
                                <button
                                    class="btn btn-sm btn-primary"
                                    data-toggle="modal"
                                    data-target="#productsModal{{ $enquiry->id }}">
                                    View Products ({{ count($enquiry->products_item) }})
                                </button>


                                @include('admin.enquiries.products-modal', [
                                'enquiry' => $enquiry
                                ])
                                @else
                                -
                                @endif
                            </td>



                            <td>₹ {{ number_format($enquiry->total_amount ?? 0, 2) }}</td>

                            <td>
                                ₹ {{ number_format(optional($enquiry->latestPayment)->amount ?? 0, 2) }}
                            </td>

                            <td>
                                {{ strtoupper(optional($enquiry->latestPayment)->method ?? 'N/A') }}
                            </td>

                            <td>
                                @if(optional($enquiry->latestPayment)->status === 'paid')
                                <span class="badge bg-success">PAID</span>
                                @else
                                <span class="badge bg-warning">PENDING</span>
                                @endif
                            </td>

                            <td>{{ $enquiry->created_at ? $enquiry->created_at->format('d M Y H:i:s') : '-' }}</td>

                            <td>
                                <form action="{{ route('enquiries.destroy', $enquiry->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Delete this enquiry?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">🗑</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="text-center">No enquiries found</td>
                        </tr>
                        @endforelse
                    </tbody>


                </table>
            </div>
        </div>
    </div>

    </div>
    </div>
    </div>


    </div>
</section>

@endsection