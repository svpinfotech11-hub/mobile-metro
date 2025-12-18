@extends('admin.admin_layout.admin_master_layout')

@section('content')

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">

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
                                    <th>Category</th>
                                    <th>Sub Category</th>
                                    <th>Service Name</th>
                                    <!-- <th>Service Banner</th> -->
                                    <!--<th>Service Icon</th>-->
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($all_service as $value)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->sub_categoryname }}</td>
                                    <td>{{ $value->service_name }}</td>
                                    <!-- <td>@if(!empty($value->service_banner_image))<img src="{{ asset('admin_assets/service_banner_image/'. $value->service_banner_image) }}" alt="{{ $value->service_banner_image }}" style="width:50px; height:50px;">@endif</td> -->
                                    <!--<td>@if(!empty($value->service_icon_image))<img src="{{ asset('admin_assets/service_icon_image/'. $value->service_icon_image) }}" alt="{{ $value->service_icon_image }}"  style="width:50px; height:50px;">@endif</td>-->

                                    <td>
                                        <button class="btn btn-sm btn-warning edit-category" data-sub_categoryname="{{ $value->sub_categoryname }}" data-service_banner_image="{{ $value->service_banner_image }}" data-service_icon_image="{{ $value->service_icon_image }}"
                                            data-service_desc="{{ $value->service_desc }}" data-category_id="{{ $value->category_id }}"
                                            data-id="{{ $value->id }}" data-name="{{ $value->service_name }}" data-subcategory_id="{{ $value->subCategory_id }}"> <i class='far fa-edit' style='font-size:15px;'></i>
                                        </button>
                                        <form action="{{ route('get-service-enquiry-destroy', $value->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">
                                                <i class='fas fa-trash-alt' style='font-size:15px;'></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">No Service found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <a href="{{ route('app.admin-services.create') }}" class="btn btn-info mt-4">Back</a>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<!-- Hidden Edit Form (Bootstrap Modal) -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editCategoryForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_id" name="id">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Services</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Category </label>
                                <select name="category_id" class="form-control" id="category_id">
                                    <option value=""> - Select - </option>
                                    @foreach($all_category as $c)
                                    <option value="{{$c->id}}">{{$c->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Sub Category </label>
                                <select name="subCategory_id" class="form-control" id="sub_category_id">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Service Name</label>
                                <input type="text" name="service_name" id="service_name" class="form-control" placeholder="Enter Service">
                            </div>
                        </div>

                        <!-- <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Service Description</label>
                                <input type="text" name="service_desc" id="service_desc" class="form-control" placeholder="Enter Service">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Upload Banner Image </label>
                                <input type="file" name="service_banner_image" class="form-control">
                                <input type="hidden" name="service_banner_image" id="service_banner_image" class="form-control">
                            </div>
                        </div> -->
                        <!--<div class="col-md-6">-->
                        <!--    <div class="form-group">-->
                        <!--        <label for="exampleInputEmail1">Upload Icon Image </label>-->
                        <!--        <input type="file" name="service_icon_image" class="form-control" >-->
                        <!--        <input type="hidden" name="service_icon_image" id="service_icon_image" class="form-control" >-->
                        <!--    </div>-->
                        <!--</div>-->

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                    <!--<button type="button" class="btn btn-secondary"  id="cancelBtn">Cancel</button>-->
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<script>
    $(document).on('click', '.edit-category', function() {
        let id = $(this).data('id');
        let name = $(this).data('name');
        let categoryId = $(this).data('category_id');
        console.log(categoryId)
        let subCategoryId = $(this).data('subcategory_id');
        console.log(subCategoryId);
        let serviceDesc = $(this).data('service_desc');
        let bannerImage = $(this).data('service_banner_image');

        // Fill basic fields
        $('#edit_id').val(id);
        $('#service_name').val(name);
        $('#service_desc').val(serviceDesc);
        $('#service_banner_image').val(bannerImage);

        // Set category
        $('#category_id').val(categoryId);

        // Load subcategories dynamically before setting selected one
        if (categoryId) {
            $.ajax({
                url: '/get-subcategories/' + categoryId,
                type: 'GET',
                success: function(data) {
                    $('#sub_category_id').empty().append('<option value="">-- Select Subcategory --</option>');
                    $.each(data, function(key, value) {
                        $('#sub_category_id').append('<option value="' + value.id + '">' + value.sub_categoryname + '</option>');
                    });

                    // Now set selected subcategory (AFTER options are loaded)
                    $('#sub_category_id').val(subCategoryId);
                }
            });
        }

        // Set form action dynamically
        $('#editCategoryForm').attr('action', "{{ route('app.admin-services.update', ':id') }}".replace(':id', id));

        // Show modal
        $('#editCategoryModal').modal('show');
    });

    $(function() {
        $('#cancelBtn').on('click', function() {
            const modalEl = document.getElementById('editCategoryModal');
            const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modal.hide();
        });
    });



    $('#category_id').on('change', function() {
        var categoryId = $(this).val();
        alert(category_id);
        if (categoryId) {
            $.ajax({
                url: '/get-subcategories/' + categoryId,
                type: 'GET',
                success: function(data) {
                    $('#sub_category_id').empty();
                    $('#sub_category_id').append('<option value="">-- Select Subcategory --</option>');
                    $.each(data, function(key, value) {
                        $('#sub_category_id').append('<option value="' + value.id + '">' + value.sub_categoryname + '</option>');
                    });
                }
            });
        } else {
            $('#sub_category_id').empty();
            $('#sub_category_id').append('<option value="">-- Select Subcategory --</option>');
        }
    });



    $('#editCategoryForm').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this); // Include file + inputs
        let actionUrl = $(this).attr('action');
        $.ajax({
            url: actionUrl,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Close modal
                $('#editCategoryModal').modal('hide');
                alert('Service updated successfully!');
                location.reload();
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                if (errors) {
                    alert('Error: ' + Object.values(errors).join(', '));
                } else {
                    alert('Something went wrong.');
                }
            }
        });
    });
</script>