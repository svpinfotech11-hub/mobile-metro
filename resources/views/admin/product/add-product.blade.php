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
                        <h3 class="card-title">Add Inventory</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="{{ route('app.admin-product.store');}}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">SERVICES </label>
                                        <select name="service_id" id="service_id" class="form-control">
                                            <option value="">- Select - </option>
                                            @foreach($services as $s)
                                            <option value="{{ $s->id }}">{{ $s->sub_categoryname }}  -  {{ $s->service_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Subcatgeory NAME</label>
                                        <input type="text" id="subcategory_name"
                                            class="form-control"
                                            placeholder="Subcategory will appear here"
                                            readonly>
                                    </div>
                                </div>

                                  <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Subcatgeory NAME</label>
                                <select name="product_subcat_id" id="product_subcat_id" class="form-control" required>
                                    <option value="">Select Product Subcategory</option>
                                </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">INVENTORY NAME</label>
                                        <input type="text" name="product_name" class="form-control" placeholder="Enter Inventory">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">INVENTORY CFT</label>
                                        <input type="text" name="product_cft" class="form-control" placeholder="Enter CFT">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary btn-sm mt-4">Submit</button>
                                </div>
                            </div>
                    </form>
                </div>
                <!-- /.card -->
            </div>

            <!-- /.card -->

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{$product_list_tit}}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Subcategory</th>
                                <th>Service </th>
                                <th>Inventory Subcategory</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($all_product as $v)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $v->service_subcategory }}</td> <!-- display subcategory -->
                                <td>{{ $v->service_name }}</td>
                                <td>{{ $v->product_subcategory }}</td>
                                <td><a href="{{ route('app.admin-product.show', $v->service_id) }}"><span class="badge badge-danger">View {{ $v->total_product }}</span></a></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">No INVENTORY Found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>


<!-- /.content -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    $('#service_id').on('change', function() {
        let serviceID = $(this).val();

        if (serviceID) {
            $.ajax({
                url: "/get-subcategory/" + serviceID,
                type: "GET",
                success: function(res) {
                    $("#subcategory_name").val(res.subcategory ?? "Not Found");
                }
            });
        } else {
            $("#subcategory_name").val("");
        }
    });
</script>


<script>
    $('#service_id').on('change', function() {
        let serviceId = $(this).val();

        if (!serviceId) return;

        $.ajax({
            url: "{{ url('/get-product-subcategory') }}/" + serviceId,
            type: "GET",
            success: function(data) {
                $('#product_subcat_id').html('<option value="">Select Subcategory</option>');
                $.each(data, function(key, item) {
                    $('#product_subcat_id').append(
                        `<option value="${item.id}">${item.subcat_name}</option>`
                    );
                });
            }
        });
    });
</script>

@endsection