@extends('admin.admin_layout.admin_master_layout')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="{{ route('app.admin-category.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Category Name</label>
                                        <input type="text" name="category_name" class="form-control"
                                            placeholder="Enter Category">
                                    </div>
                                </div>
                                <!-- <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Category Description</label>
                                        <input type="text" name="category_desc" class="form-control"
                                            placeholder="Enter Category Desc">
                                    </div>
                                </div> -->
                                {{-- <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Upload Banner Image </label>
                                        <input type="file" name="banner_img" class="form-control" >
                                    </div>
                                </div> --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Upload Icon Image </label>
                                        <input type="file" name="icon_image" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
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
                    <h3 class="card-title">{{ $category_list_tit }}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Category</th>
                                <!-- <th>Category Description</th> -->
                                <th>icon</th>
                                {{-- <th>Banner</th> --}}
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories_all as $category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $category->name }}</td>
                                <!-- <td>{{ $category->category_desc }}</td> -->
                               <td>
    <img src="{{ asset('admin_assets/category_icon_img/'. ($category->icon_image ?: 'default_image.jpg')) }}" 
         alt="{{ $category->name }}" 
         style="width:50px; height:50px;">
</td>

                                {{-- <td>
                                  @if (!empty($category->category_banner_img))
                                    <img src="{{ asset('admin_assets/category_banner_img/' . $category->category_banner_img) }}"
                                alt="{{ $category->category_banner_img }}"
                                style="width:50px; height:50px;">
                                @endif
                                </td> --}}
                                <td>
                                    <button class="btn btn-sm btn-warning edit-category"
                                        data-id="{{ $category->id }}" data-name="{{ $category->name }}"
                                        data-icon="{{ $category->icon_image }}"
                                        data-banner="{{ $category->category_banner_img }}"> <i class='far fa-edit'
                                            style='font-size:15px;'></i></button>
                                    <form action="{{ route('app.admin-category.destroy', $category->id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Are you sure?')"
                                            class="btn btn-sm btn-danger"><i class='fas fa-trash-alt'
                                                style='font-size:15px;'></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">No categories found</td>
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

<!-- Hidden Edit Form (Bootstrap Modal) -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_id" name="id">
                <input type="hidden" id="icon_image_old" name="icon_image_old">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label>Category Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>

                    <div class="col-md-12 mt-2">
                        <label>Old Icon:</label><br>
                        <img id="old_icon_preview" src="" width="80" height="80" style="border-radius:5px; object-fit:cover;">
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Upload Icon Image </label>
                            <input type="file" name="icon_image" class="form-control">
                            <input type="hidden" id="icon_image_old" name="icon_image_old">
                        </div>
                    </div>
                    {{-- <div class="col-md-3">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Upload Banner Image </label>
                        <input type="file" name="banner_img" class="form-control" >
                        <input type="text" name="banner_img" id="banner_img" class="form-control" >
                    </div>
                </div> --}}
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).on('click', '.edit-category', function() {
        let id = $(this).data('id');
        let name = $(this).data('name');
        let icon = $(this).data('icon');
        let banner = $(this).data('banner');

        $('#edit_id').val(id);
        $('#edit_name').val(name);
        $('#icon_image').val(icon);
        $('#banner_img').val(banner);

        $('#editCategoryModal').modal('show');
        $('#editCategoryForm').attr('action', "{{ route('app.admin-category.update', ':id') }}".replace(':id',
            id));
    });

    $('#editCategoryForm').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this); // Include file + inputs


        let actionUrl = $(this).attr('action');
        // alert(actionUrl);
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

                // Optionally refresh the category list or update DOM
                alert('Category updated successfully!');
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

<script>
   $(document).on('click', '.edit-category', function() {

    let id = $(this).data('id');
    let name = $(this).data('name');
    let icon = $(this).data('icon');

    $('#edit_id').val(id);
    $('#edit_name').val(name);

    // Save old image file in hidden input
    $('#icon_image_old').val(icon);

    // Show the image preview
    if (icon) {
        $('#old_icon_preview').attr('src', '/admin_assets/category_icon_img/' + icon);
    } else {
        $('#old_icon_preview').attr('src', 'https://via.placeholder.com/80?text=No+Image');
    }

    $('#editCategoryModal').modal('show');

    $('#editCategoryForm').attr(
        'action',
        "{{ route('app.admin-category.update', ':id') }}".replace(':id', id)
    );
});

</script>
@endsection