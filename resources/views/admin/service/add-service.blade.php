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
                        <h3 class="card-title">Inventory Catgeory</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="{{ route('app.admin-services.store');}}" method="POST"  enctype="multipart/form-data">
                           @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Category </label>
                                        <select name="category_id" class="form-control" id="category_id">
                                            <option value=" "> - Select - </option>
                                            @foreach($all_category as $c)
                                            <option value="{{$c->id}}">{{$c->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Sub Category </label>
                                        <select name="subCategory_id"class="form-control"  id="sub_category_id">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Service Name</label>
                                        <input type="text" name="service_name" class="form-control" placeholder="Enter Service">
                                    </div>
                                </div>
                                <!-- <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Service Description</label>
                                        <input type="text" name="service_desc" class="form-control" placeholder="Enter Service">
                                    </div>
                                </div> -->
                                 <!-- <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Upload Banner Image </label>
                                        <input type="file" name="service_banner_image" class="form-control" >
                                    </div>
                                </div> -->
                                <!--<div class="col-md-3">-->
                                <!--    <div class="form-group">-->
                                <!--        <label for="exampleInputEmail1">Upload Icon Image </label>-->
                                <!--        <input type="file" name="service_icon_image" class="form-control" >-->
                                <!--    </div>-->
                                <!--</div>-->
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
                    <h3 class="card-title">{{$service_list_tit}}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <!-- <th>Category</th> -->
                                <th>Sub Category</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($all_service as $value)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <!-- <td>{{ $value->name }}</td> -->
                                <td>{{ $value->sub_categoryname }}</td>
                                <td><a href="{{ route('app.admin-services.show',$value->subcategory_id)}}"><span class="badge badge-danger">view {{ $value->total_service }}</span></a></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">No Service found</td>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

$(document).ready(function() {
    $('#category_id').on('change', function() {
        var categoryId = $(this).val();
        if (categoryId) {
            $.ajax({
                url: '/get-subcategories/' + categoryId,
                type: 'GET',
                success: function(data) {
                    $('#sub_category_id').empty();
                    $('#sub_category_id').append('<option value="">-- Select Subcategory --</option>');
                    $.each(data, function(key, value) {
                        $('#sub_category_id').append('<option value="'+ value.id +'">'+ value.sub_categoryname +'</option>');
                    });
                }
            });
        } else {
            $('#sub_category_id').empty();
            $('#sub_category_id').append('<option value="">-- Select Subcategory --</option>');
        }
    });
});

    
</script>


@endsection