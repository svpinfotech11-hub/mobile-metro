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
                        <h3 class="card-title">KM RATE Edit</h3>
                    </div>
                    <form action="{{ route('app.admin-kmRate.update', $kmRate->id) }}" method="POST">
                        <input type="hidden" name="row_id[]" value="{{ $kmRate->id }}">

                        @csrf
                        @method('PUT')
                        <table class="table table-bordered" id="dynamicTable">
                            <tr>
                                <th>CFT</th>
                                <th>From KM</th>
                                <th>To KM</th>
                                <th>Rate</th>
                                <th>KM Profit</th>
                                <th>Rate Type</th>
                                <!-- <th>Action</th> -->
                            </tr>

                            <tr>
                                <td>
                                    <select name="cft_id[]" class="form-control">
                                        <option value="">Select</option>
                                        @foreach($cft as $v)
                                        <option value="{{ $v->id }}"
                                            {{ $v->id == $kmRate->cft_id ? 'selected' : '' }}>
                                            {{ $v->from_cft }} - {{ $v->to_cft }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>

                                <td>
                                    <input type="text" name="from_km[]"
                                        class="form-control"
                                        value="{{ $kmRate->from_km }}"
                                        placeholder="Enter FROM KM">
                                </td>

                                <td>
                                    <input type="text" name="to_km[]"
                                        class="form-control"
                                        value="{{ $kmRate->to_km }}"
                                        placeholder="Enter TO KM">
                                </td>

                                <td>
                                    <input type="text" name="km_rate[]"
                                        class="form-control"
                                        value="{{ $kmRate->km_rate }}"
                                        placeholder="Enter KM RATE">
                                </td>

                                <td>
                                    <input type="text" name="km_profit[]"
                                        class="form-control"
                                        value="{{ $kmRate->km_profit }}"
                                        placeholder="Enter KM Profit">
                                </td>

                                <td>
                                    <select name="rate_type[]" class="form-control">
                                        <option value="">Select</option>
                                        <option value="0" {{ $kmRate->rate_type == 0 ? 'selected' : '' }}>Fixed</option>
                                        <option value="1" {{ $kmRate->rate_type == 1 ? 'selected' : '' }}>PER KM</option>
                                    </select>
                                </td>

                                <!-- <td><button type="button" id="add_form" class="btn btn-success">+</button></td> -->
                            </tr>
                        </table>

                        <button type="submit" class="btn btn-primary ml-4 btn-sm mb-2">Update</button>
                        <a href="{{ route('app.admin-kmRate.showKmDetails', $cftId) }}" class="btn btn-info btn-sm mb-2">
                            Back
                        </a>
                    </form>
                </div>
                <!-- /.card -->
            </div>

            <!-- /.card -->

        </div>
    </div>
</section>



@endsection