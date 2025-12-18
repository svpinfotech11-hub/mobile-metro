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
                        <h3 class="card-title">Add Inventory SubCategory</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="{{ route('admin.product_subcategory.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>SERVICES</label>
                                          <select name="service_id" id="service_id" class="form-control">
                                            <option value="">- Select - </option>
                                            @foreach($services as $s)
                                            <option value="{{ $s->id }}">{{ $s->sub_categoryname }}  -  {{ $s->service_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Inventory SUBCATEGORY NAME</label>
                                        <input type="text" name="subcat_name" class="form-control" placeholder="Enter Subcategory Name" required>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>STATUS</label>
                                        <select name="status" class="form-control" required>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4 mt-4">
                                    <button type="submit" class="btn btn-primary btn-sm">Save Subcategory</button>
                                </div>

                            </div>
                        </div>
                    </form>

                </div>
                <!-- /.card -->


                <!-- /.card -->

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">All Record</h3>
                    </div>

                    <!-- /.card-header -->
                    <div class="card-body">

                    <form method="GET" action="{{ route('admin.product_subcategory.create') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <input 
                                    type="text" 
                                    name="search" 
                                    class="form-control" 
                                    placeholder="Search by Service Name..." 
                                    value="{{ request('search') }}"
                                >
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary">Search</button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('admin.product_subcategory.create') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Sub Catgeory</th>
                                     <th>Inventory Main Category</th>
                                    <th>Inventory Subcategory Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($all_product as $v)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                      <td>{{ $v->sub_category }}</td>
                                    <td>{{ $v->service_name }}</td>
                                    <td>{{ $v->subcat_name }}</td>
                                    <td>
                                        <a href="{{ route('admin.product_subcategory.edit', $v->id) }}" class="btn btn-sm btn-warning">Edit</a>

                                        <form action="{{ route('admin.product_subcategory.destroy', $v->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No Subcategory Found</td>
                                </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>

        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>



@endsection