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
                        <h3 class="card-title">All Record</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                       
                    <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Title</th>
                                    <!-- <th>Content</th> -->
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($policies as $policy)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $policy->type }}</td>
                                    <td>{{ $policy->title }}</td>
                                    <!-- <td>{{ $policy->content }}</td> -->
                                    <td>
                                        <a href="{{ route('policies.edit', $policy->id) }}">Edit</a>
                                        <form action="{{ route('policies.destroy', $policy->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">
                                                <i class='fas fa-trash-alt' style='font-size:15px;'></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                             
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

@endsection
