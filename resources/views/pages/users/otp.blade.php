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
                        <h3 class="card-title">OTP</h3>
                    </div>
                    <!-- form start -->
                    <form action="{{ route('users.otp.verify', $user->id);}}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="otp">Verify OTP</label>
                                        <input type="text" name="otp" class="form-control" placeholder="Enter otp.....">
                                    </div>
                                </div>
                             
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary btn-sm mt-4">Verify</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection