@extends('admin.admin_layout.admin_master_layout')

@section('content')
<!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Add New</h3>
                    </div>
                    <!-- form start -->
                    <form action="{{ route('pages.users.store');}}" method="POST" id="enquiryForm">
                        @csrf
                        <!-- <input type="hidden" name="user_id" value="{{ request('user_id') }}"> -->
                        <div class="card-body">
                            <div class="row">
                                 <div class="col-md-3">
                                    <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="name" class="form-control" required placeholder="Enter Name....">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" required placeholder="Enter Email...">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                    <label>Phone</label>
                                    <input type="text" name="phone" class="form-control" required placeholder="Enter Phone.....">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Status</label>
                                        <select name="status" class="form-control select2" style="width: 100%;">
                                            <option value="">SELECT</option>
                                            <option value="active">Active</option>
                                            <option value="inactive">InActive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Category</label>
                                        <select id="category" name="category_id" class="form-control select2" style="width: 100%;">
                                            <option value="">SELECT</option>
                                            @foreach($categories as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                     <div class="form-group">
                                    <label>Sub Category</label>
                                    <select id="subcategory" name="sub_category_id" class="form-control">
                                        <option value="">SELECT SUB CATEGORY</option>
                                    </select>
                                    </div>
                                </div>
                                </div>

                                <div class="row service-type-1 d-none">
                                    
                                <div class="col-md-3">
                                <div class="form-group">
                                <label>Date</label>
                                <input type="date" name="service_date" id="" placeholder="enter date" class="form-control">
                                </div>
                                </div>

                                <div class="col-md-2">
                                <div class="form-group">
                                <label>House / Flate No.</label>
                                <input type="text" name="floor_number" id="" placeholder="enter floor number" class="form-control">
                                </div>
                                </div>

                                <div class="col-md-5">
                                <div class="form-group">
                                <label>Area Society(Pick Up Location)</label>
                                <input type="text" name="pickup_location" id="pickup_location" placeholder="map" class="form-control">
                                </div>
                                </div>

                                <div class="col-md-2">
                                <div class="form-group">
                                    <label for="service">Lift Available</label>
                                    <select id="" name="lift_available" class="form-control">
                                    <option value="">Select an option</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                    </select>
                                </div>
                                </div>

                                <div class="col-md-5">
                                <div class="form-group">
                                <label>Area Society (Drop Location)</label>
                                <input type="text" name="drop_location" id="drop_location" placeholder="map" class="form-control">
                                </div>
                                </div>


                                <div class="col-md-6">
                                <div class="form-group">
                                <label>Services</label>
                                <select id="service" name="service_ids[]" class="form-control select2" multiple style="width:100%;">
                                <option value="">Services</option>
                                </select>
                                </div>
                                </div>

                                <div class="col-md-6 mb-2">
                                <label>Product Sub Category</label>
                                <select id="product_subcategory" name="product_subcategory_ids[]" class="form-control select2" multiple style="width:100%;">
                                    <option value="">SELECT PRODUCT SUB CATEGORY</option>
                                </select>
                                </div>

                                <div class="col-md-5 mb-2">
                                <label>Products</label>
                                <select id="products"
                                        name="products[]"
                                        class="form-control select2"
                                        multiple="multiple"
                                        style="width:100%;">
                                </select>
                                </div>

                                <div id="product_quantities" class="mt-2"></div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Payment Method</label>
                                        <select name="payment_method" class="form-control">
                                            <option value="">Select Payment Method</option>
                                            <option value="cash">Cash</option>
                                            <option value="upi">UPI</option>
                                            <option value="card">Card</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Amount</label>
                                       <input type="number"
                                        id="payable_amount"
                                        class="form-control"
                                        readonly>
                                    </div>
                                </div>
                              

                                </div>
                            

                                <!-- ===== BOX: sub_category_service = 0 ===== -->
                                <div class="row service-type-0 d-none">

                                <div class="col-md-6">
                                <div class="form-group">
                                <label>Service Name</label>
                                <input type="text" name="service_name" id="service_name" placeholder="servive name" class="form-control">
                                </div>
                                </div>

                                <div class="col-md-3">
                                <div class="form-group">
                                <label>Date</label>
                                <input type="date" name="service_date" id="date" placeholder="enter date" class="form-control">
                                </div>
                                </div>

                                <div class="col-md-6">
                                <div class="form-group">
                                <label>Service Location</label>
                                <input type="text" name="service_location" id="service_location" placeholder="map" class="form-control">
                                </div>
                                </div>

                                <div class="col-md-3">
                                <div class="form-group">
                                <label>House / Flate No.</label>
                                <input type="number" name="floor_number" id="" placeholder="enter date" class="form-control">
                                </div>
                                </div>

                                <div class="col-md-3">
                                <div class="form-group">
                                <label>Notes/Instruction</label>
                                <input type="text" name="service_description" id="service_description" placeholder="enter notes.." class="form-control">
                                </div>
                                </div>

                                </div>

                                <!-- ===== BOX: sub_category_service = 2 ===== -->
                                <div class="row service-type-2 d-none">
                                    
                                 <div class="col-md-6">
                                <div class="form-group">
                                <label>Service Name</label>
                                <input type="text" name="service_name" id="service_name" placeholder="service name" class="form-control">
                                </div>
                                </div>

                                <div class="col-md-3">
                                <div class="form-group">
                                <label>Date</label>
                                <input type="date" name="service_date" id="date" placeholder="enter date" class="form-control">
                                </div>
                                </div>

                                <div class="col-md-3">
                                <div class="form-group">
                                <label>House / Flate No.</label>
                                <input type="text" name="floor_number" id="" placeholder="enter House / Flate No." class="form-control">
                                </div>
                                </div>

                                <div class="col-md-5">
                                <div class="form-group">
                                <label>Area Society(Pick Up Location)</label>
                                <input type="text" name="pickup_location" id="pickup_locationn" placeholder="Area Society(Pick Up Location)" class="form-control">
                                </div>
                                </div>

                                <div class="col-md-5">
                                <div class="form-group">
                                <label>Area Society (Drop Location)</label>
                                <input type="text" name="drop_location" id="drop_locationn" placeholder="Area Society (Drop Location)" class="form-control">
                                </div>
                                </div>

                                <div class="col-md-3">
                                <div class="form-group">
                                <label>Notes/Instruction</label>
                                <input type="text" name="service_description" id="" placeholder="enter notes/Instruction.." class="form-control">
                                </div>
                                </div>

                                </div>

                                <!-- ===== BOX: sub_category_service = 3 ===== -->
                                <div class="row service-type-3 d-none">

                                <div class="col-md-6">
                                <div class="form-group">
                                <label>Service Name</label>
                                <input type="text" name="service_name" id="" placeholder="Service name" class="form-control">
                                </div>
                                </div>

                                <!-- future fields -->
                                <div class="col-md-3">
                                <div class="form-group">
                                <label>Service Date</label>
                                <input type="date" name="service_date" id="" placeholder="enter date" class="form-control">
                                </div>
                                </div>

                                <div class="col-md-3">
                                <div class="form-group">
                                <label>House / Flate No.</label>
                                <input type="text" name="floor_number" id="" placeholder="enter House / Flate No." class="form-control">
                                </div>
                                </div>

                                <div class="col-md-5">
                                <div class="form-group">
                                <label>Area Society(Pick Up Location)</label>
                                <input type="text" name="pickup_location" id="pickup_locationp" placeholder="Area Society(Pick Up Location)" class="form-control">
                                </div>
                                </div>

                                <div class="col-md-5">
                                <div class="form-group">
                                <label>Area Society (Drop Location)</label>
                                <input type="text" name="drop_location" id="drop_locationp" placeholder="Area Society (Drop Location)" class="form-control">
                                </div>
                                </div>

                                <div class="col-md-3">
                                <div class="form-group">
                                <label>Vehicle Model</label>
                                <input type="number" name="vehicle_number" id="" placeholder="enter vehicle number.." class="form-control">
                                </div>
                                </div>

                                </div>

                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary btn-sm mt-4">Submit</button>
                                </div>
                            </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->

<script>
$('#category').on('change', function () {
    let categoryId = $(this).val();

    console.log('Category:', categoryId);

    // RESET subcategory & service
    $('#subcategory').html('<option value="">SELECT SUB CATEGORY</option>');
    $('#service').html('<option value="">SELECT SERVICE</option>');

    if (!categoryId) return;

    $.ajax({
        url: '/get-subcategories/' + categoryId,
        type: 'GET',
        success: function (response) {
            let options = '<option value="">SELECT SUB CATEGORY</option>';

            response.forEach(function (sub) {
                // console.log(sub)
                options += `<option value="${sub.id}">
                    ${sub.sub_categoryname}
                </option>`;
            });

            $('#subcategory').html(options);
        },
        error: function (err) {
            console.error(err);
        }
    });
});
</script>

<script>
$(document).on('change', '#subcategory', function () {

    let categoryId = $('#category').val();
    let subCategoryId = $(this).val();

    console.log('Category:', categoryId, 'Subcategory:', subCategoryId);

    $('#service').html('<option value="">SELECT SERVICE</option>');

    if (!categoryId || !subCategoryId) return;

    $.ajax({
        url: '/api/get-services-all',
        type: 'GET',
        data: {
            category_id: categoryId,
            sub_category_id: subCategoryId
        },
        success: function (response) {

            let options = '<option value="">SELECT SERVICE</option>';

            response.forEach(function (service) {
                options += `<option value="${service.id}">
                    ${service.service_name}
                </option>`;
            });

            $('#service').html(options);
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            alert('Error loading services');
        }
    });
});
</script>

<!-- <script>
$(document).ready(function () {

    $('#service').on('change', function () {

        let serviceId = $(this).val();
        console.log('Service ID:', serviceId);

        $('#product_subcategory').html('<option value="">Loading...</option>');

        if (!serviceId) {
            $('#product_subcategory').html('<option value="">SELECT PRODUCT SUB CATEGORY</option>');
            return;
        }

        $.ajax({
            url: '/api/get-product-subcategories',
            type: 'GET',
            data: {
                service_id: serviceId
            },
            success: function (response) {

                let options = '<option value="">SELECT PRODUCT SUB CATEGORY</option>';

                response.forEach(function (sub) {
                    options += `<option value="${sub.id}">
                                    ${sub.subcat_name}
                                </option>`;
                });

                $('#product_subcategory').html(options);
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                alert('Failed to load product sub categories');
            }
        });
    });

});
</script> -->



<script>
$(document).ready(function () {

    $('#service').select2({
        placeholder: 'Select services',
        allowClear: true
    });

    $('#product_subcategory').select2({
        placeholder: 'Select product sub categories',
        allowClear: true
    });

    $('#products').select2({
        placeholder: 'Select products',
        allowClear: true
    });

    // 🔹 LOAD PRODUCT SUB CATEGORIES
    $('#service').on('change', function () {

        let serviceIds = $(this).val(); // ARRAY

        $('#product_subcategory').empty().trigger('change');
        $('#products').empty().trigger('change');

        if (!serviceIds || serviceIds.length === 0) {
            return;
        }

        $.ajax({
            url: '/api/get-product-subcategories',
            type: 'GET',
            data: { service_ids: serviceIds },
            success: function (response) {

                let options = response.map(sub => ({
                    id: sub.id,
                    text: sub.subcat_name
                }));

                $('#product_subcategory')
                    .empty()
                    .select2({
                        data: options,
                        placeholder: 'Select product sub categories'
                    });
            },
            error: function () {
                alert('Failed to load product sub categories');
            }
        });
    });

});
</script>






<!-- <script>
$(document).ready(function () {

    $('#products').select2({
        placeholder: 'Select products'
    });

    $('#product_subcategory').on('change', function () {

        let serviceId = $('#service').val();
        let productSubcatId = $(this).val();

        console.log(serviceId, productSubcatId);

        $('#products').empty().trigger('change');

        if (!serviceId || !productSubcatId) {
            return;
        }

        $.ajax({
            url: '/api/get-products',
            type: 'GET',
            data: {
                service_id: serviceId,
                product_subcat_id: productSubcatId
            },
            success: function (response) {

                console.log(response)

                let data = response.map(item => ({
                    id: item.id,
                    text: item.text
                }));

                $('#products').select2({
                    data: data,
                    placeholder: 'Select products'
                });
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                alert('Failed to load products');
            }
        });
    });

});
</script> -->


<script>
$(document).ready(function () {

    $('#product_subcategory').on('change', function () {

        let subCategoryIds = $(this).val(); // ARRAY

        $('#products').empty().trigger('change');

        if (!subCategoryIds || subCategoryIds.length === 0) {
            return;
        }

        $.ajax({
            url: '/api/get-products',
            type: 'GET',
            data: {
                product_subcat_ids: subCategoryIds
            },
            success: function (response) {

                let products = response.map(p => ({
                    id: p.id, // ✅ correct
                    text: `${p.text} (${p.product_cft} CFT)` // ✅ correct
                }));

                $('#products')
                    .empty()
                    .select2({
                        data: products,
                        placeholder: 'Select products'
                    });
            },
            error: function () {
                alert('Failed to load products');
            }
        });

        $('#products').on('change', function () {
    let selectedProducts = $(this).val(); // Array of selected product IDs
    let allOptions = $(this).select2('data'); // Array of selected product objects

    let html = '';
    allOptions.forEach(product => {
        html += `
            <div class="form-group row mb-2">
                <label class="col-md-8">${product.text}</label>
                <div class="col-md-4">
                    <input type="number" name="product_qty[${product.id}]" class="form-control" min="1" value="1">
                </div>
            </div>
        `;
    });

    $('#product_quantities').html(html);
    // $('#products_table').html(html);
});

    });

});
</script>


<script>
$(document).ready(function () {

    $('#products').select2({
        placeholder: 'Select products',
        width: '100%'
    });

    function resetServiceBoxes() {
        $('.service-type-0, .service-type-1, .service-type-2, .service-type-3')
            .addClass('d-none');

        $('#service, #product_subcategory').empty();
        $('#products').empty().trigger('change');
    }

    $(document).on('change', '#category', function () {
        resetServiceBoxes();
    });

    $(document).on('change', '#subcategory', function () {

        let subCategoryId = $(this).val();

        resetServiceBoxes();

        if (!subCategoryId) return;

        $.get('/api/subcategory-detail/' + subCategoryId, function (res) {

            let type = res.sub_category_service;

            $('.service-type-' + type).removeClass('d-none');

            if (type == 1) {
                loadServices();
            }
        });
    });

    function loadServices() {
        let categoryId    = $('#category').val();
        let subCategoryId = $('#subcategory').val();

        $.get('/api/get-services-all', {
            category_id: categoryId,
            sub_category_id: subCategoryId
        }, function (res) {
            $('#service').html('<option value="">Select Service</option>');
            res.forEach(s => {
                $('#service').append(
                    `<option value="${s.id}">${s.service_name}</option>`
                );
            });
        });
    }

});
</script>


<script>
function initAutocomplete() {

    var options = {
        types: ['geocode', 'establishment'],
        // componentRestrictions: { country: 'in' }
    };

    // Pickup Location
    var pickupInput = document.getElementById('pickup_location');
    if (pickupInput) {
        var pickupAutocomplete = new google.maps.places.Autocomplete(pickupInput, options);
        pickupAutocomplete.addListener('place_changed', function () {
            var place = pickupAutocomplete.getPlace();
            console.log('Pickup selected:', place.formatted_address);
        });
    }

    // Drop Location
    var dropInput = document.getElementById('drop_location');
    if (dropInput) {
        var dropAutocomplete = new google.maps.places.Autocomplete(dropInput, options);
        dropAutocomplete.addListener('place_changed', function () {
            var place = dropAutocomplete.getPlace();
            console.log('Drop selected:', place.formatted_address);
        });
    }

    // ✅ Service Location (NEW)
    var serviceInput = document.getElementById('service_location');
    if (serviceInput) {
        var serviceAutocomplete = new google.maps.places.Autocomplete(serviceInput, options);
        serviceAutocomplete.addListener('place_changed', function () {
            var place = serviceAutocomplete.getPlace();
            console.log('Service Location selected:', place.formatted_address);
        });
    }

    // ✅ Service Location (NEW)
    var serviceInputpp = document.getElementById('pickup_locationn');
    if (serviceInputpp) {
        var serviceAutocomplete = new google.maps.places.Autocomplete(serviceInputpp, options);
        serviceAutocomplete.addListener('place_changed', function () {
            var place = serviceAutocomplete.getPlace();
            console.log('Service Location selected:', place.formatted_address);
        });
    }

     // ✅ Service Location (NEW)
    var serviceInputpppp = document.getElementById('drop_locationn');
    if (serviceInputpppp) {
        var serviceAutocomplete = new google.maps.places.Autocomplete(serviceInputpppp, options);
        serviceAutocomplete.addListener('place_changed', function () {
            var place = serviceAutocomplete.getPlace();
            console.log('Service Location selected:', place.formatted_address);
        });
    }

    // ✅ Service Location (NEW)
    var serviceInputl = document.getElementById('drop_locationp');
    if (serviceInputl) {
        var serviceAutocomplete = new google.maps.places.Autocomplete(serviceInputl, options);
        serviceAutocomplete.addListener('place_changed', function () {
            var place = serviceAutocomplete.getPlace();
            console.log('Service Location selected:', place.formatted_address);
        });
    }

    // ✅ Service Location (NEW)
    var serviceInputll = document.getElementById('drop_locationp');
    if (serviceInputll) {
        var serviceAutocomplete = new google.maps.places.Autocomplete(serviceInputll, options);
        serviceAutocomplete.addListener('place_changed', function () {
            var place = serviceAutocomplete.getPlace();
            console.log('Service Location selected:', place.formatted_address);
        });
    }

    
}

google.maps.event.addDomListener(window, 'load', initAutocomplete);
</script>


<script>
$(document).ready(function () {

    $('#enquiryForm').on('submit', function (e) {
        e.preventDefault();

        let form = this;

        // ---------------------------
        // Detect service type
        // ---------------------------
        let serviceType = $('#sub_category_id option:selected').data('type');

        // ---------------------------
        // PAYMENT METHOD HANDLING
        // ---------------------------
        let paymentInputs = $('input[name="payment_method"]');

        // Reset payment
        paymentInputs.prop('required', false).prop('disabled', true);

        // Only TYPE 1 requires payment
        if (serviceType == 1) {
            paymentInputs.prop('required', true).prop('disabled', false);
        }

        // ---------------------------
        // ENABLE / DISABLE SERVICE BLOCKS
        // ---------------------------
        $('.service-type-0, .service-type-1, .service-type-2, .service-type-3').each(function () {
            const isHidden = $(this).hasClass('d-none');

            $(this)
                .find('input, select, textarea')
                .prop('disabled', isHidden);

            // Ensure payment inputs stay disabled if hidden
            if (isHidden) {
                $(this).find('input[name="payment_method"]')
                    .prop('required', false)
                    .prop('disabled', true);
            }
        });

        // ---------------------------
        // Create FormData AFTER disabling
        // ---------------------------
        let formData = new FormData(form);

        // Clear old errors
        $('.text-danger').remove();
        $('.is-invalid').removeClass('is-invalid');

        // ---------------------------
        // AJAX SUBMIT
        // ---------------------------
        $.ajax({
            url: '/api/user-enquiry/store',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,

            success: function (res) {

                // 🔹 PAYMENT REQUIRED (ONLY TYPE 1)
                if (res.status === 'payment_required') {
                    $('#payable_amount').val(res.order.amount / 100);
                    openRazorpay(res.order, res.enquiry_id);
                    return;
                }

                // 🔹 NORMAL SUCCESS
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: res.message || 'Enquiry Submitted Successfully',
                });

                form.reset();
                $('.select2').val(null).trigger('change');

                $('.service-type-0, .service-type-1, .service-type-2, .service-type-3')
                    .addClass('d-none');

                $('#enquiryForm')
                    .find('input, select, textarea')
                    .prop('disabled', false);
            },

            error: function (xhr) {

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessages = '';

                    $('.text-danger').remove();
                    $('.is-invalid').removeClass('is-invalid');

                    $.each(errors, function (field, messages) {
                        errorMessages += `<li>${messages[0]}</li>`;

                        let input = $('[name="' + field + '"]');
                        if (!input.length) {
                            input = $('[name="' + field + '[]"]');
                        }

                        if (input.length) {
                            input.addClass('is-invalid');
                            input.after(`<span class="text-danger">${messages[0]}</span>`);
                        }
                    });

                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        html: `<ul style="text-align:left">${errorMessages}</ul>`,
                    });

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Something went wrong.',
                    });
                }

                $('#enquiryForm')
                    .find('input, select, textarea')
                    .prop('disabled', false);
            }
        });
    });

});
</script>


<script>
    $('input[name="paid_amount"]').on('input', function () {
    togglePayment($(this).val());
});

function togglePayment(amount) {
    if (amount > 0) {
        $('.payment-section').removeClass('d-none');
    } else {
        $('.payment-section').addClass('d-none');
    }
}

</script>


<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<!-- <script>
function openRazorpay(order, enquiryId) {

    var options = {
        key: "{{ config('services.razorpay.key') }}",
        amount: order.amount,
        currency: "INR",
        name: "SVP Infotech",
        description: "Packers & Movers Service",
        order_id: order.id,

        handler: function (response) {

            $.post('/api/payment-success', {
                _token: '{{ csrf_token() }}',
                razorpay_payment_id: response.razorpay_payment_id,
                razorpay_order_id: response.razorpay_order_id,
                enquiry_id: enquiryId
            }, function () {

                Swal.fire({
                    icon: 'success',
                    title: 'Payment Successful',
                    text: 'Your booking is confirmed!',
                });
            });
        },

        theme: {
            color: "#0d6efd"
        }
    };

    var rzp = new Razorpay(options);
    rzp.open();
}
</script> -->


<script>
function openRazorpay(order, enquiryId) {

    var options = {
        key: "{{ config('services.razorpay.key') }}",
        amount: order.amount,
        currency: "INR",
        name: "SVP Infotech",
        description: "Packers & Movers Service",
        order_id: order.id,

        handler: function (response) {

            $.post('/api/payment-success', {
                _token: '{{ csrf_token() }}',
                razorpay_payment_id: response.razorpay_payment_id,
                razorpay_order_id: response.razorpay_order_id,
                enquiry_id: enquiryId
            }, function () {

                // ✅ SUCCESS ALERT
                Swal.fire({
                    icon: 'success',
                    title: 'Payment Successful',
                    text: 'Your booking is confirmed!',
                });

                // ==========================
                // 🔹 RESET FORM
                // ==========================
                const form = document.getElementById('enquiryForm');

                // Reset normal inputs
                form.reset();

                // Reset Select2 dropdowns
                $('.select2').val(null).trigger('change');

                // Hide service type sections
                $('.service-type-0, .service-type-1, .service-type-2, .service-type-3')
                    .addClass('d-none');

                // Hide payment section (if any)
                $('.payment-section').addClass('d-none');

                // Enable inputs (in case disabled)
                $('#enquiryForm')
                    .find('input, select, textarea')
                    .prop('disabled', false);

            });
        },

        theme: {
            color: "#0d6efd"
        }
    };

    var rzp = new Razorpay(options);
    rzp.open();
}
</script>

@endsection