@extends('admin.admin_layout.admin_master_layout')

@section('content')


<style>
    .btn-group-sm>.btn,
    .btn-sm {
        padding: 5px;
        font-size: 7px;
        line-height: 1.5;
        border-radius: .2rem;
    }

    i.fas.fa-trash-alt {
        font-size: 14px !important;
    }

    i.fa.fa-eye {
        font-size: 17px !important;
    }

    @media(max-width:768px) {
        .mainimg {
            text-align: center;
        }


        .header {
            display: inline !important;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
    }

    .mainimg img {
        width: 100px;
        height: 150px !important;
    }
</style>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Field Report</h3>
                    </div>

                    <style>
                        .enquiry-summary {
                            width: 100%;
                            border-collapse: collapse;
                            margin-top: 15px;
                            font-size: 14px;
                        }

                        .enquiry-summary th,
                        .enquiry-summary td {
                            border: 1px solid #ddd;
                            padding: 8px 10px;
                            text-align: center;
                        }

                        .enquiry-summary th {
                            background: #f3f3f3;
                            font-weight: 600;
                        }

                        .section-header {
                            background: #eef9f3;
                            font-weight: bold;
                            text-align: center;
                        }

                        .totals-row {
                            background: #f9f9f9;
                            font-weight: bold;
                        }

                        .grand-total-row {
                            background: #dff0d8;
                            font-weight: bold;
                            font-size: 15px;
                        }

                        body {
                            font-family: Arial, sans-serif;
                            margin: 20px;
                        }

                        .header {
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            border-bottom: 2px solid #000;
                            padding-bottom: 10px;
                            margin-bottom: 20px;
                        }

                        .header img {
                            height: 60px;
                        }

                        h2 {
                            margin: 0;
                        }

                        .section {
                            margin-bottom: 20px;
                        }

                        .section h3 {
                            margin-bottom: 10px;
                            border-bottom: 1px solid #ccc;
                            padding-bottom: 5px;
                        }

                        table {
                            border-collapse: collapse;
                            width: 100%;
                            margin-top: 10px;
                        }

                        table,
                        th,
                        td {
                            border: 1px solid #ddd;
                        }

                        th,
                        td {
                            padding: 8px;
                            text-align: left;
                        }

                        th {
                            background: #f2f2f2;
                        }

                        .footer {
                            margin-top: 30px;
                            font-size: 12px;
                            text-align: center;
                            color: #555;
                        }

                        @media print {
                            body * {
                                visibility: hidden;
                            }

                            #printSection,
                            #printSection * {
                                visibility: visible;
                            }

                            #printSection {
                                position: absolute;
                                left: 0;
                                top: 0;
                                width: 100%;
                            }
                        }

                        .inventory-section {
                            margin-top: 25px;
                        }

                        .section-title {
                            font-size: 18px;
                            font-weight: 700;
                            border-bottom: 1px solid #ddd;
                            padding-bottom: 8px;
                            margin-bottom: 15px;
                        }

                        .inventory-table {
                            width: 100%;
                            border-collapse: collapse;
                            font-size: 14px;
                        }

                        .inventory-table th {
                            background: #f5f5f5;
                            text-align: left;
                            padding: 10px;
                            border: 1px solid #ddd;
                            font-weight: 700;
                        }

                        .inventory-table td {
                            padding: 10px;
                            border: 1px solid #ddd;
                        }

                        .text-center {
                            text-align: center;
                        }

                        /* Footer Section */
                        .inventory-footer {
                            margin-top: 15px;
                            position: relative;
                            min-height: 45px;
                        }

                        .total-qty {
                            font-size: 20px;
                            font-weight: 700;
                            color: #000;
                        }

                        /* Quotation Bar */
                        .quotation-bar {
                            position: absolute;
                            right: 0;
                            bottom: 0;
                            background: #dff0d8;
                            padding: 8px 15px;
                            font-weight: 700;
                            display: flex;
                            gap: 10px;
                            align-items: center;
                            border-radius: 3px;
                        }

                        .quotation-bar .amount {
                            font-size: 16px;
                        }
                    </style>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div style="text-align:right; margin-bottom:20px;">
                            <button type="button" onclick="window.print()"
                                style="padding:10px 20px; background:#4CAF50; color:#fff; border:none; cursor:pointer;">
                                🖨️ Print Invoice
                            </button>
                        </div>

                        <div id="printSection">
                            <div class="header">
                                <div>
                                    <h2>Field Report</h2>
                                    <!-- <p><strong>Invoice / Quotation</strong></p> -->
                                </div>

                                <div class="mainimg">
                                    <img src="{{ asset('images/mobilemetrologo.jpeg') }}" alt="">
                                </div>

                                <div>
                                    <!-- Replace with your logo -->
                                    <img src="https://www.svpinfotech.com/images/logo.png" alt="Company Logo">
                                    <!-- <img src="{{ public_path('images/logo.png') }}" alt="Company Logo"> -->
                                </div>
                            </div>

                            <!-- Customer Details -->
                            <div class="row">
                                <div class="section col-md-4">
                                    <h3>Customer Details</h3>
                                    <p><strong>Name:</strong> {{ $customer->customer_name }}</p>
                                    <p><strong>Email:</strong> {{ $customer->email }}</p>
                                    <p><strong>Phone:</strong> {{ $customer->mobile_no }}</p>
                                    <p><strong>City:</strong> {{ $customer->city }}</p>
                                    <p><strong>Pincode:</strong> {{ $customer->pincode }}</p>
                                </div>

                                <div class="section col-md-4">
                                    <h3>Enquiry Details</h3>
                                    <p><strong>Shifting Date:</strong> {{ \Carbon\Carbon::parse($enquiry->shipping_date_time) }}</p>
                                    <p><strong>Flat Number:</strong> {{ $enquiry->flat_no ?? '' }}</p>
                                    <p><strong>Source Address:</strong> {{ $enquiry->pickup_location }}</p>
                                    <p><strong>Pickup Floor:</strong> {{ $enquiry->floor_number }}</p>
                                    <p><strong>Latitude:</strong> {{ $enquiry->pickup_lat }}</p>
                                    <p><strong>Longitude:</strong> {{ $enquiry->pickup_lng }}</p>
                                    <p><strong>Destination Address:</strong> {{ $enquiry->drop_location }}</p>
                                    <p><strong>Destination Floor Pickup:</strong> {{ $enquiry->destination_floor_number }}</p>
                                    <p><strong>Pickup Service Lift:</strong> {{ $enquiry->pickup_services_lift ? 'Yes' : 'No' }}</p>
                                    <p><strong>Drop Service Lift:</strong> {{ $enquiry->drop_services_lift ? 'Yes' : 'No' }}</p>
                                    <p><strong>Latitude:</strong> {{ $enquiry->drop_lat }}</p>
                                    <p><strong>Longitude:</strong> {{ $enquiry->drop_lng }}</p>
                                </div>

                                <div class="section col-md-4 mt-5">
                                    <!-- <h3>Payment Summary</h3> -->

                      <p><strong>Total KM:</strong> {{ $enquiry->km_distance }}</p>


                                    <p><strong>Total CFT:</strong> {{ $enquiry->total_cft }}</p>


                                    <p><strong>Quotation / Total Amount:</strong>
                                        ₹{{ $quotationAmount }}
                                    </p>

                                    <p><strong>Total Advance Paid:</strong>
                                        ₹{{ $advancePaid }}
                                    </p>

                                    <p><strong>Balance Amount:</strong>
                                        ₹{{ $quotationAmount - $advancePaid }}
                                    </p>

                                    <p><strong>Payment ID:</strong>
                                        {{ $lastPayment->razorpay_payment_id ?? 'N/A' }}
                                    </p>

                                    <p><strong>Payment Status:</strong>
                                        {{ ucfirst($lastPayment->payment_status ?? 'N/A') }}
                                    </p>

                                    <p><strong>Payment Date:</strong>
                                        {{ isset($lastPayment->payment_date)
                                        ? \Carbon\Carbon::parse($lastPayment->payment_date)->format('d-m-Y h:i A')
                                        : 'N/A'
                                    }}
                                    </p>
                                </div>
                            </div>

                            <!-- INVENTORY SELECTED -->
                            <div class="section inventory-section">
                                <h3 class="section-title">INVENTORY SELECTED</h3>

                                <table class="inventory-table">
                                    <thead>
                                        <tr>
                                            <th>ITEM NAME</th>
                                            <th class="text-center">QUANTITY</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $totalQty = 0; @endphp

                                        @foreach(($enquiry->items ?? []) as $item)
                                        @php $totalQty += (int) $item['quantity']; @endphp
                                        <tr>
                                            <td>{{ strtoupper($item['product_name']) }}</td>
                                            <td class="text-center">{{ $item['quantity'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <!-- TOTAL + QUOTATION BAR -->
                                <div class="inventory-footer">
                                    <div class="total-qty">
                                        TOTAL QTY - {{ $totalQty }}
                                    </div>

                                    <div class="quotation-bar">
                                        <span>QUOTATION AMOUNT :</span>
                                        <span class="amount">₹ {{ number_format($quotationAmount) }}</span>
                                    </div>
                                </div>
                            </div>



                            <!-- Footer -->
                            <div class="footer">
                                <p>Thank you for choosing Packers & Movers. For any queries, contact us at support@packersmovers.com</p>
                            </div>
                        </div>
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