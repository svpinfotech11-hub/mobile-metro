@extends('admin.admin_layout.admin_master_layout')

@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary mt-2">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title }}</h3>
                    </div>

                    <form 
                        action="{{ isset($cftRates) && count($cftRates) ? route('app.admin-cftRate.update', $cftRates[0]->id) : route('app.admin-cftRate.store') }}" 
                        method="POST">
                        @csrf
                        @if(isset($cftRates) && count($cftRates))
                            @method('PUT')
                        @endif

                        <table class="table table-bordered" id="dynamicTable">
                            <tr>
                                <th>From CFT</th>
                                <th>To CFT</th>
                                <th>Rate</th>
                                <th>CFT Profit</th>
                                <th>Rate Type</th>
                               
                            </tr>

                            @if(isset($cftRates) && count($cftRates))
                                @foreach($cftRates as $key => $rate)
                                <tr>
                                    <td>
                                        <input type="hidden" name="row_id[]" value="{{ $rate->id }}">
                                        <input type="text" name="from_cft[]" value="{{ $rate->from_cft }}" class="form-control" />
                                    </td>
                                    <td><input type="text" name="to_cft[]" value="{{ $rate->to_cft }}" class="form-control" /></td>
                                    <td><input type="text" name="cft_rate[]" value="{{ $rate->cft_rate }}" class="form-control" /></td>
                                    <td><input type="text" name="cft_profit[]" value="{{ $rate->cft_profit }}" class="form-control" /></td>
                                    <td>
                                        <select name="rate_type[]" class="form-control">
                                            <option value="">Select</option>
                                            <option value="0" {{ $rate->rate_type == 0 ? 'selected' : '' }}>Fixed</option>
                                            <option value="1" {{ $rate->rate_type == 1 ? 'selected' : '' }}>PER CFT</option>
                                        </select>
                                    </td>
                                    
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td><input type="text" name="from_cft[]" class="form-control" /></td>
                                    <td><input type="text" name="to_cft[]" class="form-control" /></td>
                                    <td><input type="text" name="cft_rate[]" class="form-control" /></td>
                                    <td><input type="text" name="cft_profit[]" class="form-control" /></td>
                                    <td>
                                        <select name="rate_type[]" class="form-control">
                                            <option value="">Select</option>
                                            <option value="0">Fixed</option>
                                            <option value="1">PER CFT</option>
                                        </select>
                                    </td>
                                    <td><button type="button" id="add_form" class="btn btn-success">+</button></td>
                                </tr>
                            @endif
                        </table>

                        <button type="submit" class="btn btn-primary ml-4 btn-sm mb-2">Submit</button>
                        <a href="{{ route('app.admin-cftRate.index') }}" class="btn btn-primary btn-sm mb-2">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>


                    </form>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@endpush
