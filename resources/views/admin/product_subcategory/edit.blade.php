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
                        <h3 class="card-title">Edit Product SubCategory</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="{{ route('admin.product_subcategory.update', $subcategory->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>SERVICES</label>
                                        <select name="service_id" class="form-control" required>
                                            <option value="">- Select Service -</option>
                                            @foreach($services as $s)
                                            <option value="{{ $s->id }}"
                                                {{ $subcategory->service_id == $s->id ? 'selected' : '' }}>
                                            {{ $s->sub_categoryname }}  -  {{ $s->service_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>PRODUCT SUBCATEGORY NAME</label>
                                        <input type="text"
                                            name="subcat_name"
                                            class="form-control"
                                            value="{{ old('subcat_name', $subcategory->subcat_name) }}"
                                            required>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>STATUS</label>
                                        <select name="status" class="form-control" required>
                                            <option value="1" {{ $subcategory->status == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ $subcategory->status == 0 ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4 mt-4">
                                    <button type="submit" class="btn btn-success btn-sm">Update Subcategory</button>
                                    <a href="{{ route('admin.product_subcategory.create') }}" class="btn btn-secondary btn-sm">Back</a>
                                </div>

                            </div>
                        </div>
                    </form>


                </div>
                <!-- /.card -->
            </div>

        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>



@endsection