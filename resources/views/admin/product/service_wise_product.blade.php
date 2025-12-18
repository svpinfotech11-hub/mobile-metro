@extends('admin.admin_layout.admin_master_layout')

@section('content')

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->

            <!-- /.card -->
            <div class="col-md-12">
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
                                    <th>Service</th>
                                    <th>Product Subcategory</th>
                                    <th>Inventory</th>
                                    <th>Inventory CFT</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $v)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $v->service_name }}</td>
                                    <td>{{ $v->product_subcategory }}</td>
                                    <td>{{ $v->product_name }}</td>
                                    <td>{{ $v->product_cft }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning edit-category"
                                            data-cft="{{ $v->product_cft }}"
                                            data-service="{{ $v->service_id }}"
                                            data-id="{{ $v->product_id }}"
                                            data-name="{{ $v->product_name }}">
                                            <i class='far fa-edit' style='font-size:15px;'></i>
                                        </button>

                                        <form action="{{ route('app.admin-product.destroy', $v->product_id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">
                                                <i class='fas fa-trash-alt' style='font-size:15px;'></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                                @if($products->isEmpty())
                                <tr>
                                    <td colspan="5" class="text-center">No INVENTORY Found</td>
                                </tr>
                                @endif
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
                <input type="hidden" id="edit_id" name="product_id">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Inventory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                 <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="exampleInputEmail1">SERVICES </label>
                            <select name="service_id" id="edit_service" class="form-control">
                                <option value="">- Select - </option>
                                @foreach($services as $s)
                                <option value="{{ $s->id }}">{{ $s->service_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Inventory NAME</label>
                            <input type="text" name="product_name" id="edit_name" class="form-control" placeholder="Enter Inventory">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Inventory CFT</label>
                            <input type="text" name="product_cft" id="product_cft" class="form-control" placeholder="Enter CFT">
                        </div>
                    </div>
                    
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
                    <a href="{{ route('app.admin-product.create') }}" class="btn btn-secondary ms-3">Back</a>
@endsection
 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<script>
$(document).on('click', '.edit-category', function () {
    let id = $(this).data('id');
    let name = $(this).data('name');
    let service = $(this).data('service');
    let cft = $(this).data('cft');
    
    $('#edit_id').val(id);
    $('#edit_name').val(name);
    $('#edit_service').val(service);
    $('#product_cft').val(cft);

    $('#editCategoryModal').modal('show');
    $('#editCategoryForm').attr('action', "{{ route('app.admin-product.update', ':id') }}".replace(':id', id));
});

$(function() {
  $('#cancelBtn').on('click', function() {
    const modalEl = document.getElementById('editCategoryModal');
    const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
    modal.hide();
  });
});
</script>