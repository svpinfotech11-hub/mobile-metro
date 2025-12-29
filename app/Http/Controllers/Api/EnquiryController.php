<?php

namespace App\Http\Controllers\Api;

use App\Models\CFTModel;
use App\Models\EnquiryModel;
use Illuminate\Http\Request;
use App\Models\ProductSubCategory;
use Illuminate\Support\Facades\DB;
use App\Models\EnquiryserviceModel;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\ProductModel;
use Illuminate\Support\Facades\Validator;

class EnquiryController extends Controller
{
  
    public function store(Request $request)
    {
        Log::info('🔹 Enquiry Store API called', ['request' => $request->all()]);

        // Validation
        $validator = Validator::make($request->all(), [
            'customer_id'          => 'required|integer',
            'pickup_location'      => 'required|string',
            'pickup_lat'           => 'nullable|numeric',
            'pickup_lng'           => 'nullable|numeric',
            'drop_location'        => 'required|string',
            'drop_lat'             => 'nullable|numeric',
            'drop_lng'             => 'nullable|numeric',
            'flat_shop_no'         => 'required|string',
            'shipping_date_time'   => 'required|date',
            'floor_number'         => 'required|integer',
            'pickup_services_lift' => 'required|boolean',
            'drop_services_lift'   => 'required|boolean',
            'vehicle_number'       => 'nullable|string|max:50',
            'notes'                => 'nullable|string',
            'products_item'        => 'required|array|min:1',
            'products_item.*.product_name' => 'required|string',
            'products_item.*.quantity'     => 'required|integer|min:1',
            'destination_floor_number'     => 'nullable',
            'products_item.*.product_subcat_id' => 'required|integer|exists:tbl_product_subcategory,id'
        ]);

        if ($validator->fails()) {
            Log::warning('⚠️ Validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => false,
                'msg'    => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $maxId = DB::table('tbl_enquiry')->max('id');
            $nextId = ($maxId ?? 0) + 1;
            $order_no = 666 + $nextId;

            $totalAmount = 0;
            $totalCft = 0;
            $items = $request->input('products_item', []);

            // Process each product and calculate total CFT
            foreach ($items as &$item) {
                $productName = trim($item['product_name']);
                $quantity = (int) $item['quantity'];

                $product = DB::table('tbl_product')
                    ->whereRaw('LOWER(product_name) = ?', [strtolower($productName)])
                    ->first();

                if (!$product) {
                    Log::warning('⚠️ Product not found', ['product' => $productName]);
                    continue; // Skip if product not found
                }

                $productCft = (float) $product->product_cft;
                $totalProductCft = $productCft * $quantity;

                $rateDetails = $this->getCftRate($totalProductCft);
                $totalAmount += $rateDetails['total_cost'];

                // Add calculated fields to each product
                $item['product_cft'] = $productCft;
                $item['total_cft'] = $totalProductCft;
                $item['rate_type'] = $rateDetails['rate_type'];
                $item['cft_rate'] = $rateDetails['cft_rate'];
                $item['cft_profit'] = $rateDetails['cft_profit'];
                $item['total_cost'] = $rateDetails['total_cost'];

                $item['product_id'] = $product->product_id;
                $item['service_id'] = $product->service_id;
                $item['product_subcat_id'] = isset($item['product_subcat_id']) 
                ? (int)$item['product_subcat_id'] 
                : ($product->product_subcat_id ?? 0);      
                $totalCft += $totalProductCft;
            }

            // Use the first product's cft_id for KM calculation
            $firstProduct = DB::table('tbl_product')
                ->whereRaw('LOWER(product_name) = ?', [strtolower($items[0]['product_name'])])
                ->first();

            if (!$firstProduct) {
                return response()->json([
                    'status' => false,
                    'msg'    => 'CFT ID not found for first product'
                ], 422);
            }

            $cft_id = $firstProduct->product_cft ?? null;

            // Calculate KM distance
            if (!empty($request->pickup_lat) && !empty($request->drop_lat)) {
                $km_distance = $this->calculateDistanceForService(
                    $request->pickup_lat,
                    $request->pickup_lng,
                    $request->drop_lat,
                    $request->drop_lng
                );
                $distanceSource = 'haversine';
            } else {
                $pickupGeo = $this->getLatLngFromGoogle($request->pickup_location);
                $dropGeo   = $this->getLatLngFromGoogle($request->drop_location);

                $request->merge([
                    'pickup_lat' => $pickupGeo['lat'],
                    'pickup_lng' => $pickupGeo['lng'],
                    'drop_lat'   => $dropGeo['lat'],
                    'drop_lng'   => $dropGeo['lng'],
                ]);

                $km_distance = $this->calculateDistanceFromGoogle(
                    $request->pickup_location,
                    $request->drop_location
                );

                $distanceSource = 'google';
            }

            Log::info('📏 Distance calculated', [
                'distance' => $km_distance,
                'source'   => $distanceSource
            ]);

            // Get KM rate
            $kmRateData = $this->getKmRate($km_distance, $cft_id);
            Log::info('💰 KM rate details', $kmRateData);

            $grandTotal = $totalAmount + $kmRateData['total_km_cost'];

            // Prepare data for insertion
            $data = [
                'order_no'             => $order_no,
                'customer_id'          => $request->customer_id,
                'pickup_location'      => $request->pickup_location,
                'pickup_lat'           => $request->pickup_lat,
                'pickup_lng'           => $request->pickup_lng,
                'drop_location'        => $request->drop_location,
                'drop_lat'             => $request->drop_lat,
                'drop_lng'             => $request->drop_lng,
                'destination_floor_number' => $request->destination_floor_number,
                'flat_shop_no'         => $request->flat_shop_no,
                'shipping_date_time'   => date('Y-m-d H:i:s', strtotime($request->shipping_date_time)),
                'floor_number'         => $request->floor_number,
                'pickup_services_lift' => $request->pickup_services_lift,
                'drop_services_lift'   => $request->drop_services_lift,
                'vehicle_number'       => $request->vehicle_number,
                'notes'                => $request->notes,
                'total_cft'            => $totalCft,
                'products_item'        => json_encode($items),
                'km_distance'          => round($km_distance),
                'rate_type'            => $kmRateData['rate_type'],
                'km_rate'              => $kmRateData['km_rate'],
                'km_profit'            => $kmRateData['km_profit'],
                'total_km_cost'        => round($kmRateData['total_km_cost']),
                'total_amount'         => round($grandTotal),
                'created_at'           => now(),
                'updated_at'           => now(),
            ];

            Log::info('📝 Prepared data for insertion', ['data' => $data]);

            $enquiry = EnquiryModel::create($data);

            Log::info('✅ Enquiry created successfully', ['enquiry_id' => $enquiry->id]);

            return response()->json([
                'status'        => true,
                'msg'           => 'Enquiry created successfully',
                'data'          => $enquiry,
                'product_total' => round($totalAmount, 2),
                'km_rate'       => $kmRateData,
                'total_cft'     => $totalCft,
                'grand_total'   => round($grandTotal, 2),
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Product Enquiry Error', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status'  => false,
                'msg'     => 'Something went wrong while creating the enquiry.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    private function getLatLngFromGoogle($address)
    {
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($address) . "&key={$apiKey}";

        $response = @file_get_contents($url);
        if ($response === false) {
            Log::error("❌ Google Geocode API call failed for: " . $address);
            return ['lat' => null, 'lng' => null];
        }

        $data = json_decode($response, true);
        if (!empty($data['results'][0]['geometry']['location'])) {
            return $data['results'][0]['geometry']['location']; // ['lat' => ..., 'lng' => ...]
        }

        Log::warning("⚠️ No coordinates found for: " . $address);
        return ['lat' => null, 'lng' => null];
    }


    // public function storeServiceEnquiry(Request $request)
    // {
    //     $request->validate([
    //         'customer_id' => 'required',
    //         'service_description' => 'required|string',
    //         'flat_no' => 'required|string|max:255',
    //         'service_location' => 'required|string',
    //         'service_name' => 'required|string|max:100',
    //         'service_date' => 'required|date',
    //         'pickup_location' => 'required|string',
    //         'pickup_lat' => 'nullable|numeric', // ✅ changed to nullable
    //         'pickup_lng' => 'nullable|numeric',
    //         'drop_location' => 'required|string',
    //         'drop_lat' => 'nullable|numeric',
    //         'drop_lng' => 'nullable|numeric',
    //         'vehicle_number' => 'nullable|string|max:50',
    //         'notes' => 'nullable|string',
    //         'shipping_date_time' => 'nullable|date',
    //     ]);

    //     try {
    //         // Generate order number
    //         $maxId = DB::table('enquiry_service_tbl')->max('id');
    //         $nextId = ($maxId ?? 0) + 1;
    //         $order_no = 666 + $nextId;

    //         // Calculate distance
    //         if (!empty($request->pickup_lat) && !empty($request->drop_lat)) {
    //             // ✅ Use Haversine formula if coordinates present
    //             $kmDistance = $this->calculateDistanceForService(
    //                 $request->pickup_lat,
    //                 $request->pickup_lng,
    //                 $request->drop_lat,
    //                 $request->drop_lng
    //             );
    //             $distanceSource = 'haversine';
    //         } else {
    //             // ✅ Use Google API if coordinates missing
    //             $kmDistance = $this->calculateDistanceFromGoogle(
    //                 $request->pickup_location,
    //                 $request->drop_location
    //             );
    //             $distanceSource = 'google';
    //         }

    //         $cft_id = $firstProduct->product_cft ?? null;

    //         // Get rate details
    //         $rateInfo = $this->getKmRate($kmDistance, $cft_id);

    //         // Total amount
    //         $totalAmount = $rateInfo['total_km_cost'];

    //         // Store data
    //         $serviceEnquiry = EnquiryserviceModel::create([
    //             'order_no'           => $order_no,
    //             'customer_id'        => $request->customer_id,
    //             'service_description' => $request->service_description,
    //             'flat_no'            => $request->flat_no,
    //             'service_location'   => $request->service_location,
    //             'service_name'       => $request->service_name,
    //             // 'service_date'       => $request->service_date,
    //             'service_date' => date('Y-m-d', strtotime($request->service_date)),
    //             'pickup_location'    => $request->pickup_location,
    //             'pickup_lat'         => $request->pickup_lat,
    //             'pickup_lng'         => $request->pickup_lng,
    //             'drop_location'      => $request->drop_location,
    //             'drop_lat'           => $request->drop_lat,
    //             'drop_lng'           => $request->drop_lng,
    //             'vehicle_number'     => $request->vehicle_number,
    //             'notes'              => $request->notes,
    //             'shipping_date_time' => $request->shipping_date_time,
    //             'km_distance'        => round($kmDistance, 2),
    //             'rate_type'          => $rateInfo['rate_type'],
    //             'km_rate'            => $rateInfo['km_rate'],
    //             // 'km_profit'          => $rateInfo['km_profit'],
    //             'total_amount'       => round($totalAmount, 2),
    //         ]);
            

    //         // ✅ Success response
    //         return response()->json([
    //             'status'  => true,
    //             'message' => 'Service enquiry created successfully!',
    //             'data'    => $serviceEnquiry
    //         ]);
    //     } catch (\Exception $e) {
    //         // Log the error for debugging
    //         Log::error('Service Enquiry Error: ' . $e->getMessage());

    //         // Return clean JSON error
    //         return response()->json([
    //             'status'  => false,
    //             'message' => 'Something went wrong while creating service enquiry.',
    //             'error'   => $e->getMessage()
    //         ], 500);
    //     }
    // }


      public function storeServiceEnquiry(Request $request)
{
    // 1. Validation
    $request->validate([
        'customer_id'        => 'required',
        'service_description'=> 'required|string',
        'flat_no'            => 'required|string|max:255',
        'service_location'   => 'required|string',
        'service_name'       => 'required|string|max:100',
        'service_date'       => 'required|date',
        'pickup_location'    => 'required|string',
        'pickup_lat'         => 'nullable|numeric',
        'pickup_lng'         => 'nullable|numeric',
        'drop_location'      => 'required|string',
        'drop_lat'           => 'nullable|numeric',
        'drop_lng'           => 'nullable|numeric',
        'vehicle_number'     => 'nullable|string|max:50',
        'notes'              => 'nullable|string',
        'shipping_date_time' => 'nullable|date',
    ]);

    try {

        // 2. Create order number
        $maxId = DB::table('enquiry_service_tbl')->max('id');
        $nextId = ($maxId ?? 0) + 1;
        $order_no = 666 + $nextId;

        // 3. INSERT ONLY — NO CALCULATIONS
        $serviceEnquiry = EnquiryserviceModel::create([
            'order_no'           => $order_no,
            'customer_id'        => $request->customer_id,
            'service_description'=> $request->service_description,
            'flat_no'            => $request->flat_no,
            'service_location'   => $request->service_location,
            'service_name'       => $request->service_name,
            'service_date'       => $request->service_date,
            'pickup_location'    => $request->pickup_location,
            'pickup_lat'         => $request->pickup_lat,
            'pickup_lng'         => $request->pickup_lng,
            'drop_location'      => $request->drop_location,
            'drop_lat'           => $request->drop_lat,
            'drop_lng'           => $request->drop_lng,
            'vehicle_number'     => $request->vehicle_number,
            'notes'              => $request->notes,
            'shipping_date_time' => $request->shipping_date_time,
        ]);

        // 4. Success Response
        return response()->json([
            'status'  => true,
            'message' => 'Service enquiry created successfully!',
            'data'    => $serviceEnquiry
        ]);

    } catch (\Exception $e) {

        Log::error('Service Enquiry Error: '.$e->getMessage());

        return response()->json([
            'status'  => false,
            'message' => 'Something went wrong while creating service enquiry.',
            'error'   => $e->getMessage()
        ], 500);
    }
}

    private function calculateDistanceForService($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // in kilometers

        $lat1 = deg2rad($lat1);
        $lng1 = deg2rad($lng1);
        $lat2 = deg2rad($lat2);
        $lng2 = deg2rad($lng2);

        $dLat = $lat2 - $lat1;
        $dLng = $lng2 - $lng1;

        $a = sin($dLat / 2) ** 2 +
            cos($lat1) * cos($lat2) * sin($dLng / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c; // distance in KM
    }

    private function calculateDistanceFromGoogle($pickup, $drop)
    {
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        $pickupEncoded = urlencode($pickup);
        $dropEncoded = urlencode($drop);

        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins={$pickupEncoded}&destinations={$dropEncoded}&key={$apiKey}";
        $response = @file_get_contents($url);

        if ($response === false) {
            Log::error("Google Maps API call failed for URL: " . $url);
            return 0;
        }

        $data = json_decode($response, true);
        if (!empty($data['rows'][0]['elements'][0]['distance']['value'])) {
            return $data['rows'][0]['elements'][0]['distance']['value'] / 1000; // meters to km
        }

        return 0;
    }

    // public function customerEnquiryList(Request $request, $customer_id)
    // {
    //     // $id = $request->customer_id;
    //     // $enquiry_list = DB::table('tbl_enquiry')->where('customer_id', $customer_id)->get();
    //     // // Decode JSON before sending
    //     // foreach ($enquiry_list as $enquiry) {
    //     //     $enquiry->products_item = json_decode($enquiry->products_item, true);
    //     // }
    //     // return response()->json(['status' => true, 'msg' => 'View Customer Enquiry', 'data' => $enquiry_list]);

    //     // Fetch all enquiries for this customer
    //     $enquiries = EnquiryModel::with('customer:id,customer_name,mobile_no,email')
    //         ->where('customer_id', $customer_id)
    //         ->orderByDesc('id')
    //         ->get();

    //     // Decode products and append CFT info
    //     $enquiries->transform(function ($item) {
    //         $products = json_decode($item->products_item, true);
    //         $totalCft = 0;

    //         if (is_array($products)) {
    //             foreach ($products as &$product) {
    //                 $productInfo = DB::table('tbl_product')
    //                     ->where('product_name', $product['product_name'])
    //                     ->select('product_cft')
    //                     ->first();

    //                 $product['product_cft'] = $productInfo->product_cft ?? 0;

    //                 // Multiply quantity × product_cft
    //                 $quantity = isset($product['quantity']) ? (int)$product['quantity'] : 1;
    //                 $totalCft += $quantity * ($product['product_cft'] ?? 0);
    //             }
    //         }

    //         $item->products_item = $products;
    //         $item->total_cft = $totalCft; // ✅ Add total CFT to each enquiry
    //         $item->created_date = $item->created_at->format('Y-m-d H:i:s');

    //         return $item;
    //     });

    //     if ($enquiries->isEmpty()) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'No enquiries found for this customer.'
    //         ], 404);
    //     }

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Customer enquiries fetched successfully.',
    //         'data' => $enquiries
    //     ]);
    // }

    public function customerEnquiryList(Request $request)
    {
        $id = $request->customer_id;
        // Fetch all enquiries for this customer with customer info and payments
        $enquiries = EnquiryModel::with([
            'customer:id,customer_name,mobile_no,email',
            'payments'  // include payments
        ])
            ->where('customer_id', $id)
            ->orderByDesc('id')
            ->get();

        // Decode products and append CFT info
        $enquiries->transform(function ($item) {
            $products = json_decode($item->products_item, true);
            $totalCft = 0;

            if (is_array($products)) {
                foreach ($products as &$product) {
                    $productInfo = DB::table('tbl_product')
                        ->where('product_name', $product['product_name'])
                        ->select('product_cft')
                        ->first();

                    $product['product_cft'] = $productInfo->product_cft ?? 0;

                    // Multiply quantity × product_cft
                    $quantity = isset($product['quantity']) ? (int)$product['quantity'] : 1;
                    $totalCft += $quantity * ($product['product_cft'] ?? 0);
                }
            }

            $item->products_item = $products;
            $item->total_cft = $totalCft;
            $item->created_date = $item->created_at;

            // Add total payments amount
            $item->total_paid = $item->payments->sum('amount');
            // $item->payments_detail = $item->payments->map(function($payment) {
            //     return [
            //         'order_no' => $payment->order_no,
            //         'amount' => $payment->amount,
            //         'currency' => $payment->currency,
            //         'payment_status' => $payment->payment_status,
            //         'status_text' => $payment->status_text,
            //         'payment_method' => $payment->payment_method,
            //         'qr_code_url' => $payment->qr_code_url,
            //         'paid' => $payment->is_paid,
            //         'created_at' => $payment->created_at->format('Y-m-d H:i:s'),
            //     ];
            // });

            $item->payments_detail = $item->payments->map(function ($payment) {
                return [
                    'order_no' => $payment->order_no,
                    'amount' => $payment->amount,
                    'currency' => $payment->currency,
                    'payment_status' => $payment->payment_status,
                    'status_text' => $payment->status_text,
                    'payment_method' => $payment->payment_method,
                    'qr_code_url' => $payment->qr_code_url,
                    'razorpay_payment_id' => $payment->razorpay_payment_id, // ADDED
                    'payment_date' => $payment->payment_date,               // ADDED
                    'paid' => $payment->is_paid,
                    'created_at' => $payment->created_at,
                ];
            });


            return $item;
        });

        if ($enquiries->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No enquiries found for this customer.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Customer enquiries with payments fetched successfully.',
            'data' => $enquiries
        ]);
    }


    private function getCftRate($product_cft)
    {
        $rateInfo = CFTModel::whereRaw('CAST(from_cft AS UNSIGNED) <= ?', [$product_cft])
            ->whereRaw('CAST(to_cft AS UNSIGNED) >= ?', [$product_cft])
            ->first();

        // ✅ If no matching slab found, return 0 for all (don't pick nearest lower slab)
        if (!$rateInfo) {
            return [
                'cft_rate' => 0,
                'cft_profit' => 0,
                'total_cost' => 0,
                'total_amount' => 0,
                'rate_type' => 'N/A'
            ];
        }

        // Determine rate type
        $rateType = ($rateInfo->rate_type == 0) ? 'fixed' : 'per box';
        $rate = $rateInfo->cft_rate;
        $profit = $rateInfo->cft_profit;

        // Calculation
        if ($rateType === 'fixed') {
            // Fixed rate — base rate only
            $totalAmount = $rate;
            $totalCost = $rate + $profit;
        } else {
            // Per box — rate multiplied by CFT
            $totalAmount = $rate * $product_cft;
            $totalCost = $totalAmount + $profit;
        }

        return [
            'cft_rate' => $rate,
            'cft_profit' => $profit,
            'total_cost' => $totalCost,
            'total_amount' => $totalAmount,
            'rate_type' => $rateType
        ];
    }


    public function myRequests($customer_id)
    {
        // Fetch all enquiries for this customer
        $enquiries = EnquiryModel::with('customer:id,customer_name,mobile_no,email')
            ->where('customer_id', $customer_id)
            ->orderByDesc('id')
            ->get();

        // Decode products and append CFT info
        $enquiries->transform(function ($item) {
            $products = json_decode($item->products_item, true);
            $totalCft = 0;

            if (is_array($products)) {
                foreach ($products as &$product) {
                    $productInfo = DB::table('tbl_product')
                        ->where('product_name', $product['product_name'])
                        ->select('product_cft')
                        ->first();

                    $product['product_cft'] = $productInfo->product_cft ?? 0;

                    // Multiply quantity × product_cft
                    $quantity = isset($product['quantity']) ? (int)$product['quantity'] : 1;
                    $totalCft += $quantity * ($product['product_cft'] ?? 0);
                }
            }

            $item->products_item = $products;
            $item->total_cft = $totalCft; // ✅ Add total CFT to each enquiry
            $item->created_date = $item->created_at->format('Y-m-d H:i:s');

            return $item;
        });

        if ($enquiries->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No enquiries found for this customer.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Customer enquiries fetched successfully.',
            'data' => $enquiries
        ]);
    }

    private function getKmRate($km_distance, $cft_id)
    {
        // Round up distance to nearest whole number (optional)
        $km_distance = ceil($km_distance);

        $rateInfo = DB::table('km_rate_tb')
        ->where('cft_id', $cft_id)
        ->where('from_km', '<=', $km_distance)
        ->where('to_km', '>=', $km_distance)
        ->first();


        if (!$rateInfo) {
            return [
                'km_rate'       => 0,
                'km_profit'     => 0,
                'total_km_cost' => 0,
                'rate_type'     => 'N/A',
                'rate_profile'  => 0
            ];
        }

        $rateType = ($rateInfo->rate_type == 0) ? 'fixed' : 'per Km';

        $rate   = (float) $rateInfo->km_rate;
        $profit = (float) $rateInfo->km_profit;

        // Correct calculation
        if ($rateType === 'fixed') {
            $totalKmCost = $rate;
        } else { // per box
            $totalKmCost = $rate + $profit;
        }

        // Add rate_profile as sum of rate + profit
        $rate_profile = $rate + $profit;

        return [
            'km_rate'       => $rate,
            'km_profit'     => $profit,
            'total_km_cost' => $totalKmCost,
            'rate_type'     => $rateType,
            'rate_profile'  => $rate_profile
        ];
    }

    public function getSubcategoriesByService($service_id)
    {
        $subcategories = ProductSubCategory::join('tbl_services', 'tbl_services.id', '=', 'tbl_product_subcategory.service_id')
            ->where('tbl_product_subcategory.service_id', $service_id)
            ->where('tbl_product_subcategory.status', 1)
            ->get([
                'tbl_product_subcategory.id as subcat_id',
                'tbl_product_subcategory.subcat_name',
                'tbl_product_subcategory.service_id',
                'tbl_services.service_name'
            ]);

        return response()->json([
            'status' => true,
            'data' => $subcategories
        ]);
    }



    public function getProducts(Request $request)
    {
        $request->validate([
            'service_id' => 'required|integer|exists:tbl_services,id',
            'subcat_id'  => 'nullable|integer|exists:tbl_product_subcategory,id',
        ]);

        $productsQuery = ProductModel::join('tbl_services', 'tbl_services.id', '=', 'tbl_product.service_id')
            ->join('tbl_product_subcategory', 'tbl_product_subcategory.id', '=', 'tbl_product.product_subcat_id')
            ->where('tbl_product.service_id', $request->service_id)
            ->where('tbl_product.status', 1);

        if ($request->subcat_id) {
            $productsQuery->where('tbl_product.product_subcat_id', $request->subcat_id);
        }

        $products = $productsQuery->get([
            'tbl_product.product_id',
            'tbl_product.product_name',
            'tbl_product.product_cft',
            'tbl_product.service_id',
            'tbl_services.service_name',
            'tbl_product.product_subcat_id',
            'tbl_product_subcategory.subcat_name as product_subcat_name'
        ]);

        return response()->json([
            'status' => true,
            'data' => $products
        ]);
    }

    public function showdata($id)
    {
        $enquiry = EnquiryModel::findOrFail($id);

        return response()->json([
            'status' => true,
            'data' => $enquiry
        ]);
    }

    public function updateData(Request $request, $id)
    {
        // Log::info('🔄 UPDATE ENQUIRY HIT', ['request' => $request->all()]);

        // ✅ SAME validation as store (except customer/order fields)
        $validator = Validator::make($request->all(), [
            'service_id' => 'required|integer|exists:tbl_services,id',
            'products_item' => 'required|array|min:1',
            'products_item.*.product_name' => 'required|string',
            'products_item.*.quantity' => 'required|integer|min:1',
            'products_item.*.product_subcat_id' => 'required|integer|exists:tbl_product_subcategory,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'msg'    => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $enquiry = EnquiryModel::findOrFail($id);

            $totalAmount = 0;
            $totalCft = 0;
            $items = $request->products_item;

            // ✅ PROCESS PRODUCTS (SAME AS STORE)
            foreach ($items as &$item) {
                $productName = trim($item['product_name']);
                $quantity = (int) $item['quantity'];

                $product = DB::table('tbl_product')
                    ->whereRaw('LOWER(product_name) = ?', [strtolower($productName)])
                    ->where('service_id', $request->service_id)
                    ->first();

                if (!$product) {
                    throw new \Exception("Product '{$productName}' not found for this service");
                }

                $productCft = (float) $product->product_cft;
                $totalProductCft = $productCft * $quantity;

                $rateDetails = $this->getCftRate($totalProductCft);

                $totalAmount += $rateDetails['total_cost'];
                $totalCft += $totalProductCft;

                // ✅ Normalize product data (same as store)
                $item['product_id'] = $product->product_id;
                $item['service_id'] = $product->service_id;
                $item['product_cft'] = $productCft;
                $item['total_cft'] = $totalProductCft;
                $item['rate_type'] = $rateDetails['rate_type'];
                $item['cft_rate'] = $rateDetails['cft_rate'];
                $item['cft_profit'] = $rateDetails['cft_profit'];
                $item['total_cost'] = $rateDetails['total_cost'];
            }

            // ✅ GET FIRST PRODUCT FOR KM CALC (SAME AS STORE)
            $firstProduct = DB::table('tbl_product')
                ->whereRaw('LOWER(product_name) = ?', [strtolower($items[0]['product_name'])])
                ->first();

            if (!$firstProduct) {
                throw new \Exception('CFT ID not found for KM calculation');
            }

            $cft_id = $firstProduct->product_cft;

            // ✅ DISTANCE CALC (SAME AS STORE)
            if (!empty($enquiry->pickup_lat) && !empty($enquiry->drop_lat)) {
                $km_distance = $this->calculateDistanceForService(
                    $enquiry->pickup_lat,
                    $enquiry->pickup_lng,
                    $enquiry->drop_lat,
                    $enquiry->drop_lng
                );
            } else {
                $km_distance = $this->calculateDistanceFromGoogle(
                    $enquiry->pickup_location,
                    $enquiry->drop_location
                );
            }

            // ✅ KM RATE (SAME AS STORE)
            $kmRateData = $this->getKmRate($km_distance, $cft_id);
            $grandTotal = $totalAmount + $kmRateData['total_km_cost'];

            // ✅ UPDATE ENQUIRY
            $enquiry->update([
                'service_id'    => $request->service_id,
                'products_item' => json_encode($items),
                'total_cft'     => $totalCft,
                'km_distance'   => round($km_distance),
                'rate_type'     => $kmRateData['rate_type'],
                'km_rate'       => $kmRateData['km_rate'],
                'km_profit'     => $kmRateData['km_profit'],
                'total_km_cost' => round($kmRateData['total_km_cost']),
                'total_amount'  => round($grandTotal),
            ]);

            DB::commit();

            Log::info('✅ Enquiry updated successfully', [
                'enquiry_id' => $enquiry->id
            ]);

            return response()->json([
                'status'        => true,
                'msg'           => 'Enquiry updated successfully',
                'data'          => $enquiry->fresh(),
                'product_total' => round($totalAmount, 2),
                'km_rate'       => $kmRateData,
                'total_cft'     => $totalCft,
                'grand_total'   => round($grandTotal, 2),
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('❌ Enquiry update failed', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => false,
                'msg'    => $e->getMessage()
            ], 422);
        }
    }


}
