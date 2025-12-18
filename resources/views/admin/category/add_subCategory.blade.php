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
                        <h3 class="card-title">{{$title}}</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="{{ route('app.admin-subCategory.store');}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Category</label>
                                        <select class="form-control select2" name="category_id" style="width: 100%;">
                                            <option value="">--select--</option>
                                             @forelse($categories_all as $category)
                                             <option value="{{ $category->id }}">{{ $category->name }}</option>
                                             @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Sub Category Name</label>
                                        <input type="text" name="sub_categoryname" class="form-control" placeholder="Enter Sub Category">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Sub Category Description</label>
                                        <input type="text" name="sub_category_desc" class="form-control" placeholder="Enter Sub Description">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Upload Banner Image </label>
                                        <input type="file" name="sub_banner_image" class="form-control" >
                                    </div>
                                </div>
                                 <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Upload Icon Image </label>
                                        <input type="file" name="sub_icon_image" class="form-control" >
                                    </div>
                                </div> 
                                <div class="col-md-3">
                                <div class="form-group">
                                    <label>Sub Category Type</label>
                                    <select name="sub_category_service" class="form-control">
                                        <option value="0">Service</option>
                                        <option value="1">Product</option>
                                        <option value="2">Shifting</option>
                                        <option value="3">Transportation</option>
                                    </select>
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
                    <h3 class="card-title">{{$subcategory_list_tit}}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Category</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sub_categories_all as $v)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $v->name }}</td>
                                <td>
                                 <a href="{{ route('app.admin-subCategory.show', $v->category_id) }}">
                                    <button class="btn btn-sm btn-warning show-category"><a href="{{ route('app.admin-subCategory.show' , $v->category_id); }}"> View     <span class="badge badge-danger">{{ $v->total_subcategories }}</span></button>
                                </a>
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





@endsection