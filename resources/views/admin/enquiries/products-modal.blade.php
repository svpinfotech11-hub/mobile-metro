<div class="modal fade" id="productsModal{{ $enquiry->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Products – Enquiry #{{ $enquiry->id }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>CFT / Item</th>
                            <th>Total CFT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $grandTotalCft = 0;
                        @endphp

                        @foreach($enquiry->products_item as $index => $product)
                        @php
                        $grandTotalCft += $product['total_cft'] ?? 0;
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $product['product_name'] ?? '-' }}</td>
                            <td>{{ $product['quantity'] ?? 0 }}</td>
                            <td>{{ $product['product_cft'] ?? 0 }}</td>
                            <td>{{ $product['total_cft'] ?? 0 }}</td>
                        </tr>
                        @endforeach

                        <!-- Grand Total Row -->
                        <tr>
                            <td colspan="4" class="text-right"><strong>Total CFT:</strong></td>
                            <td><strong>{{ $grandTotalCft }}</strong></td>
                        </tr>
                    </tbody>

                </table>

                @php
                $grandTotalCft = 0;

                // Sum total CFT from all products
                foreach($enquiry->products_item as $product) {
                $grandTotalCft += $product['total_cft'] ?? 0;
                }

                // Fetch the applicable CFT rate from DB for this total CFT
                $cftRateData = \DB::table('cft_rate_tbl')
                ->where('from_cft', '<=', $grandTotalCft)
                    ->where('to_cft', '>=', $grandTotalCft)
                    ->first();

                    $cftRate = $cftRateData->cft_rate ?? 0;
                    $cftProfit = $cftRateData->cft_profit ?? 0;
                    $rateType = $cftRateData->rate_type == 0 ? 'PER CFT' : 'PER CFT'; // adjust if you have different types

                    $totalCftCost = ($grandTotalCft * $cftRate) + $cftProfit;
                    @endphp

                    <div class="mt-4">
                        <h5>CFT Based Charges</h5>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <td><strong>Total CFT:</strong></td>
                                    <td>{{ $grandTotalCft }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Rate Type:</strong></td>
                                    <td>{{ $rateType }}</td>
                                </tr>
                                <tr>
                                    <td><strong>CFT Rate:</strong></td>
                                    <td>₹ {{ number_format($cftRate) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>CFT Profit:</strong></td>
                                    <td>₹ {{ number_format($cftProfit) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total CFT Cost:</strong></td>
                                    <td>₹ {{ number_format($totalCftCost) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>


                    @php
                    // KM / Distance based charges
                    $kmDistance = $enquiry->km_distance ?? 0;
                    $kmRate = $enquiry->km_rate ?? 0;
                    $kmProfit = $enquiry->km_profit ?? 0;
                    $kmRateType = $enquiry->km_rate_type ?? 'PER KM';

                    $totalKmCost = ($kmDistance * $kmRate) + $kmProfit;
                    @endphp

                    @if($kmDistance > 0)
                    <div class="mt-4">
                        <h5>Distance Based Charges</h5>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <td><strong>Distance (KM):</strong></td>
                                    <td>{{ number_format($kmDistance) }} KM</td>
                                </tr>
                                <tr>
                                    <td><strong>Rate Type:</strong></td>
                                    <td>{{ $kmRateType }}</td>
                                </tr>
                                <tr>
                                    <td><strong>KM Rate:</strong></td>
                                    <td>₹ {{ number_format($kmRate) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>KM Profit:</strong></td>
                                    <td>₹ {{ number_format($kmProfit) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total KM Cost:</strong></td>
                                    <td>₹ {{ number_format($totalKmCost) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-2">
                        <h5>Grand Total (Products + KM):</h5>
                        <p>₹ {{ number_format($totalCftCost + $totalKmCost) }}</p>
                    </div>
                    @endif


            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>