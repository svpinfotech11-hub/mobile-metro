@extends('admin.admin_layout.admin_master_layout')

@section('content')


  <style>
    .btn-group-sm>.btn, .btn-sm {
    padding: 5px;
    font-size: 7px;
    line-height: 1.5;
    border-radius: .2rem;
    }i.fas.fa-trash-alt {
        font-size: 14px !important;
    }

    i.fa.fa-eye {
        font-size: 17px !important;
    }
  </style>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title }}</h3>
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
                                    <h2>Packers & Movers</h2>
                                    <p><strong>Invoice / Quotation</strong></p>
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

                                <div class="section col-md-4">
                                <h3>Payment Summary</h3>
                            
                                <p><strong>Total KM:</strong> {{ $enquiry->km_distance }}</p>
                            
                                
                                <p><strong>Total CFT:</strong> {{ $totalCft }}</p>
                            
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

                            <!-- Items -->
                            <div class="section">
                                <h3>INVENTORY Selected</h3>

                                <!-- ===========================
                                PRODUCT INVENTORY SECTION
                                  =========================== -->
                                <table class="enquiry-summary invoice-table">

                                    <tr class="section-title">
                                        <td colspan="6">INVENTORY SELECTED</td>
                                    </tr>

                                    <thead>
                                        <tr>
                                            <th>ITEM NAME</th>
                                            <th>QUANTITY</th>
                                            <th>Inventory CFT</th>
                                            <th>TOTAL CFT</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach(($enquiry->items ?? []) as $item)
                                        <tr>
                                            <td>{{ strtoupper($item['product_name']) }}</td>
                                            <td>{{ $item['quantity'] }}</td>
                                            <td>{{ number_format($item['product_cft']) }}</td>
                                            <td>{{ number_format($item['total_cft']) }}</td>
                                        </tr>
                                        @endforeach

                                        <tr class="totals-row highlight">
                                            <td colspan="3" style="text-align:right; font-weight:700;">TOTAL CFT:</td>
                                            <td style="font-weight:700;">{{ number_format($enquiry->total_cft) }}</td>
                                        </tr>
                                    </tbody>
                                </table>


                                <!-- ===========================
                                    CFT BASED CHARGES SECTION
                                =========================== -->
                         
                                <table class="enquiry-summary charges-table">

                                    <tr class="section-header bg-light-green">
                                        <td colspan="6">CFT BASED CHARGES</td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" style="text-align:right;">TOTAL CFT :</td>
                                        <td colspan="3">{{ number_format($enquiry->total_cft) }}</td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" style="text-align:right;">RATE TYPE :</td>
                                        <td colspan="3">{{ $rateType }}</td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" style="text-align:right;">CFT RATE :</td>
                                        <td colspan="3">{{ number_format($cftRate) }}</td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" style="text-align:right;">CFT PROFIT :</td>
                                        <td colspan="3">{{ number_format($cftProfit) }}</td>
                                    </tr>

                                    <tr class="totals-row">
                                        <td colspan="3" style="text-align:right; font-weight:600;">
                                            TOTAL CFT COST :
                                        </td>
                                        <td colspan="3">{{ number_format($totalCftCost) }}</td>
                                    </tr>

                                </table>


                                <!-- ===========================
                                    DISTANCE BASED CHARGES
                                =========================== -->
                                <table class="enquiry-summary charges-table">

                                    <tr class="section-header bg-light-green">
                                        <td colspan="6">DISTANCE BASED CHARGES</td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" style="text-align:right;">DISTANCE (KM):</td>
                                        <td colspan="3">{{ number_format($enquiry->km_distance) }} KM</td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" style="text-align:right;">RATE TYPE:</td>
                                        <td colspan="3">PER KM</td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" style="text-align:right;">KM RATE:</td>
                                        <td colspan="3">{{ number_format($enquiry->km_rate) }}</td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" style="text-align:right;">KM PROFIT:</td>
                                        <td colspan="3">{{ number_format($enquiry->km_profit ?? 0) }}</td>
                                    </tr>

                                    <tr class="totals-row">
                                        <td colspan="3" style="text-align:right; font-weight:600;">TOTAL KM COST :</td>
                                        <td colspan="3">{{ number_format($enquiry->total_km_cost) }}</td>
                                    </tr>
                                </table>


                                <!-- ===========================
                                        GRAND TOTAL
                                =========================== -->
                                <table class="enquiry-summary totals-footer">

                                    <tr class="grand-total-row">
                                        <td colspan="5" style="text-align:right; font-size:20px; font-weight:700;">
                                            GRAND TOTAL (PRODUCTS + KM):
                                        </td>
                                        <td style="font-size:20px; font-weight:700;">
                                            {{ number_format($enquiry->grand_total_cost) }}
                                        </td>
                                    </tr>

                                </table>
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