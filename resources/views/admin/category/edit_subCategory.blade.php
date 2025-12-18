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
                    <form action="{{ route('app.admin-subCategory.update', $subcategor_details->id) }}"    method="POST"  enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Category</label>
                                        <select class="form-control select2" name="category_id" style="width: 100%;">
                                            <option value="">--select--</option>
                                             @forelse($categories_all as $category)
                                             <option value="{{ $category->id }}" @selected($category->id == $subcategor_details->category_id)>{{ $category->name }}</option>
                                             @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Sub Category Name</label>
                                        <input type="text" name="sub_categoryname" value="{{ $subcategor_details->sub_categoryname }}" class="form-control" placeholder="Enter Sub Category">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Sub Category Description</label>
                                        <input type="text" name="sub_category_desc" class="form-control" value="{{ $subcategor_details->sub_category_desc }}" placeholder="Enter Sub Description">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Upload Banner  Image </label>
                                        <input type="file" name="sub_banner_image" class="form-control" >
                                          @if($subcategor_details->sub_banner_image)
                                                <small>Current Image:</small><br>
                                                <img src="{{ asset('admin_assets/subcategories/'.$subcategor_details->sub_banner_image) }}" 
                                                     alt="Subcategory Image" 
                                                     style="max-width:100px; margin-top:5px;">
                                            @endif
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Upload Icon  Image </label>
                                        <input type="file" name="sub_icon_image" class="form-control" >
                                          @if($subcategor_details->sub_icon_image)
                                                <small>Current Image:</small><br>
                                                <img src="{{ asset('admin_assets/subcategoriesIconImg/'.$subcategor_details->sub_icon_image) }}" 
                                                     alt="Subcategory Image" 
                                                     style="max-width:100px; margin-top:5px;">
                                            @endif
                                    </div>
                                </div>
                                 <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Sub Category Type</label>
                                            <select name="sub_category_service" class="form-control">
                                                <option value="0"
                                                    {{ $subcategor_details->sub_category_service == 0 ? 'selected' : '' }}>
                                                    Service</option>
                                                <option value="1"
                                                    {{ $subcategor_details->sub_category_service == 1 ? 'selected' : '' }}>
                                                    Product</option>
                                                <option value="2"
                                                    {{ $subcategor_details->sub_category_service == 2 ? 'selected' : '' }}>
                                                    Shifting</option>
                                                <option value="3"
                                                    {{ $subcategor_details->sub_category_service == 3 ? 'selected' : '' }}>
                                                    Transportation</option>
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

        

        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->





@endsection