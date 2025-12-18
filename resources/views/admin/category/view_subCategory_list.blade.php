@extends('admin.admin_layout.admin_master_layout')
@section('content')

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">{{ $title }}</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>CATEGORY </th>
                  <th>SUB CATEGORY</th>
                  <th>SUB CATEGORY DESRIPTION</th>
                  <th>BANNER IMAGE</th>
                  <th>ICON IMAGE</th>
                  <th>SERVICE Type</th>
                  <th>ACTION</th>
                </tr>
              </thead>
              <tbody>
                @foreach($sub_categories_details as $sc)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $sc->name }}</td>
                  <td>{{ $sc->sub_categoryname }}</td>
                  <td>{{ $sc->sub_category_desc }}</td>
                  <td><img src="{{ asset('admin_assets/subcategories/'.$sc->sub_banner_image) }}" alt="{{ $sc->sub_banner_image }}" style="width:50px; height:50px;"></td>
                  <td>
                    @if(!empty($sc->sub_icon_image))
                    <img src="{{ asset('admin_assets/subcategoriesIconImg/'.$sc->sub_icon_image) }}" alt="{{ $sc->sub_icon_image }}" style="width:50px; height:50px;">
                    @endif
                  </td>

                  <td>{{ $sc->sub_category_service ? 'Yes' : 'No' }}</td>
                  @php
                  $typeLabels = [
                  0 => 'Service',
                  1 => 'Product',
                  2 => 'Shifting',
                  3 => 'Transportation',
                  ];
                  @endphp

                  <!-- <td>{{ $typeLabels[$sc->sub_category_service] ?? '' }}</td> -->
                  <td>
                    <!-- <button class="btn btn-sm btn-warning edit-category" data-id="{{ $sc->category_id  }}" data-name="{{ $sc->name }}"> <i class='far fa-edit' style='font-size:15px;'></i></button> -->
                    <a href="{{ route('app.admin-subCategory.edit', $sc->id) }}" class="btn btn-primary btn-sm">Edit</a>
                    <form action="{{ route('app.admin-subCategory.destroy', $sc->id ) }}" method="POST" style="display:inline;">
                      @csrf
                      @method('DELETE')
                      <button onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger"><i class='fas fa-trash-alt' style='font-size:15px;'></i></button>
                    </form>
                  </td>
                </tr>
                @endforeach
            </table>
               <a href="{{ route('app.admin-subCategory.create') }}" class="btn btn-info mt-4">Back</a>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection